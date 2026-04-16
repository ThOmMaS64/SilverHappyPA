package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

func ShowPersonalizedServicesPage(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		research := r.FormValue("research")
		filter := r.FormValue("filter")
		sort := r.FormValue("sort")

		response := ResponseService{
			Services: []Service{},
		}

		id := r.FormValue("id")

		var args []any
		 
		basicQuery := "SELECT SERVICE.ID_SERVICE, SERVICE.type, SERVICE.description, COALESCE(SERVICE.place, ''), COALESCE(SERVICE.cost, 0.0), SERVICE.is_medical_confidential, SERVICE.requires_date, SERVICE.pricing_type, (USER_INTERACTION_SERVICE.ID_USER IS NOT NULL) AS is_saved, USER_.name, USER_.surname, SERVICE_PROVIDER.ID_SERVICE_PROVIDER FROM SERVICE INNER JOIN OFFER ON SERVICE.ID_SERVICE = OFFER.ID_SERVICE INNER JOIN SERVICE_PROVIDER ON OFFER.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER LEFT JOIN USER_INTERACTION_SERVICE ON SERVICE.ID_SERVICE = USER_INTERACTION_SERVICE.ID_SERVICE AND USER_INTERACTION_SERVICE.ID_USER = ? WHERE 1=1"
		args = append(args, id)

		if research != ""{

			basicQuery += " AND (SERVICE.type LIKE CONCAT('%', ?, '%') OR SERVICE.description LIKE CONCAT('%', ?, '%'))"
			args = append(args, research, research)

		}

		if filter != ""{

			basicQuery += " AND SERVICE.type = ?"
			args = append(args, filter)

		}

		if sort == "1"{

			basicQuery += " ORDER BY SERVICE.cost ASC"

		}else if sort == "2"{

			basicQuery += " ORDER BY SERVICE.cost DESC"

		}else if sort =="3"{

			rowSelectTypes, errSelecTypes := database.Query("SELECT SERVICE.type FROM SERVICE JOIN USER_INTERACTION_SERVICE ON SERVICE.ID_SERVICE = USER_INTERACTION_SERVICE.ID_SERVICE WHERE USER_INTERACTION_SERVICE.ID_USER = ?", id)
	
			if errSelecTypes != nil{

				w.WriteHeader(500)
				response.Error = "Erreur lors de la récupération des prestations depuis la base de donnée."
				json.NewEncoder(w).Encode(response)
				return 

			}

			defer rowSelectTypes.Close()

			var types []string

			for rowSelectTypes.Next(){

				var serviceType string

				err := rowSelectTypes.Scan(&serviceType)

				if err == nil{

					types = append(types, serviceType)

				}
			}

			if len(types) > 0 {

				basicQuery += " ORDER BY ("
				
				for i:=0; i<len(types); i++{

					if i != 0 {
						
						basicQuery += " OR "

					}

					basicQuery += "SERVICE.type = ?"
					args = append(args, types[i])

				}

				basicQuery += ") DESC"

			}

		}

		rowSelectServices, errSelectServices := database.Query(basicQuery, args...)
	
		if errSelectServices != nil{

			response.Error = "Erreur lors de la récupération des prestations depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectServices.Close()

		for rowSelectServices.Next(){

			var service Service
			service.Slots = []ServiceSlots{}

			err := rowSelectServices.Scan(&service.ID_SERVICE, &service.Type, &service.Description, &service.Place, &service.Cost, &service.IsMedicalConfidential, &service.RequiresDate, &service.PricingType, &service.IsSaved, &service.ServiceProviderName, &service.ServiceProviderSurname, &service.IdServiceProvider)

			if err != nil{

				response.Error = "Erreur lors de la récupération des prestations depuis la base de donnée."
				json.NewEncoder(w).Encode(response)
				return

			}else{

				if service.RequiresDate {

					rowSlots, errSlots := database.Query("SELECT ID_SERVICE_SLOT, start_time, end_time FROM SERVICE_SLOT WHERE ID_SERVICE = ? AND ID_SERVICE_PROVIDER = ? AND is_booked = 0 AND start_time > NOW() AND start_time <= DATE_ADD(NOW(), INTERVAL 14 DAY)", service.ID_SERVICE, service.IdServiceProvider)

					if errSlots != nil{

						w.WriteHeader(500)
						response.Error = "Erreur lors de la récupération des prestations depuis la base de donnée."
						json.NewEncoder(w).Encode(response)
						return 

					}else{

						for rowSlots.Next(){

							var slot ServiceSlots
							
							err := rowSlots.Scan(&slot.ID_SERVICE_SLOT, &slot.StartTime, &slot.EndTime)

							if err != nil{

								w.WriteHeader(500)
								response.Error = "Erreur lors de la récupération des prestations depuis la base de donnée."
								json.NewEncoder(w).Encode(response)
								return 

							}
							
							service.Slots = append(service.Slots, slot)

						}

						rowSlots.Close()

					}

				}

				response.Services = append(response.Services, service)

			}
		}

		json.NewEncoder(w).Encode(response)

	}

}