package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ServiceSlots struct{

	ID_SERVICE_SLOT int `json:"id_service_slot"`
	StartTime string `json:"start_time"`
	EndTime string `json:"end_time"`

}

type Service struct {

	ID_SERVICE int `json:"ID_SERVICE"`
	Type string `json:"type"`
	Description string `json:"description"`
	Cost float64 `json:"cost"`
	IsMedicalConfidential bool `json:"is_medical_confidential"`
	IsSaved bool `json:"is_saved"`
	RequiresDate bool `json:"requires_date"`
	PricingType string `json:"pricing_type"`
	Slots []ServiceSlots `json:"slots"`
	IdServiceProvider int `json:"ID_SERVICE_PROVIDER"`
	ServiceProviderName string `json:"service_provider_name"`
	ServiceProviderSurname string `json:"service_provider_surname"`
	IsAtConsumerHome bool `json:"is_at_consumer_home"`
	City string `json:"city"`
	Street string `json:"street"`
	NbStreet int `json:"nb_street"`
	PostalCode string `json:"postal_code"`

}

type ResponseService struct {

	Types []string `json:"types"`
	Services []Service `json:"services"`
	Error string `json:"error"`

}

func ShowDefaultServicesPage(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseService{
			Types: []string{},
			Services: []Service{},
		}

		id := r.FormValue("id")

		rowSelectType, errSelectType := database.Query("SELECT DISTINCT type FROM SERVICE ORDER BY type ASC")
	
		if errSelectType != nil {

			response.Error = "Erreur lors de la récupération des types depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectType.Close()

		for rowSelectType.Next(){

			var serviceType string

			err := rowSelectType.Scan(&serviceType)

			if err == nil{

				response.Types = append(response.Types, serviceType)

			}

		}
		

		rowSelectServices, errSelectServices := database.Query("SELECT SERVICE.ID_SERVICE, SERVICE.type, SERVICE.description, COALESCE(SERVICE.cost, 0.0), SERVICE.is_medical_confidential, SERVICE.requires_date, SERVICE.pricing_type, (USER_INTERACTION_SERVICE.ID_USER IS NOT NULL) AS is_saved, USER_.name, USER_.surname, SERVICE_PROVIDER.ID_SERVICE_PROVIDER, SERVICE.is_at_consumer_home, COALESCE(WORK_ADDRESS.city, ''), COALESCE(WORK_ADDRESS.street, ''), COALESCE(WORK_ADDRESS.nb_street, 0), COALESCE(WORK_ADDRESS.postal_code, '') FROM SERVICE INNER JOIN OFFER ON SERVICE.ID_SERVICE = OFFER.ID_SERVICE INNER JOIN SERVICE_PROVIDER ON OFFER.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER LEFT JOIN WORK_ADDRESS ON SERVICE.ID_WORK_ADDRESS = WORK_ADDRESS.ID_WORK_ADDRESS LEFT JOIN USER_INTERACTION_SERVICE ON SERVICE.ID_SERVICE = USER_INTERACTION_SERVICE.ID_SERVICE AND USER_INTERACTION_SERVICE.ID_USER = ?", id)
	
		if errSelectServices != nil{

			w.WriteHeader(500)
			response.Error = "Erreur lors de la récupération des prestations depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectServices.Close()

		for rowSelectServices.Next(){

			var service Service
			service.Slots = []ServiceSlots{}

			err := rowSelectServices.Scan(&service.ID_SERVICE, &service.Type, &service.Description, &service.Cost, &service.IsMedicalConfidential, &service.RequiresDate, &service.PricingType, &service.IsSaved, &service.ServiceProviderName, &service.ServiceProviderSurname, &service.IdServiceProvider, &service.IsAtConsumerHome, &service.City, &service.Street, &service.NbStreet, &service.PostalCode)

			if err != nil{

				w.WriteHeader(500)
				response.Error = "Erreur lors de la récupération des prestations depuis la base de donnée."
				json.NewEncoder(w).Encode(response)
				return 

			}

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

		json.NewEncoder(w).Encode(response)
		 
	}

}