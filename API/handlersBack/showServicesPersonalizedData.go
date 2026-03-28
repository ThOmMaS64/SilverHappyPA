package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

func ShowServicesPersonalizedData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		research := r.FormValue("research")
		filter := r.FormValue("filter")
		sort := r.FormValue("sort")
		
		offsetString := r.FormValue("offset")
		offset := 0

		if offsetString != ""{

			offset, _ = strconv.Atoi(offsetString)

		}

		response := ResponseServices{
			Types: []string{},
			Services: []Service{},
		}

		rowSelectType, errSelectType := database.Query("SELECT DISTINCT type FROM SERVICE ORDER BY type ASC")
	
		if errSelectType != nil {

			http.Error(w, "Erreur lors de la récupération des Types depuis la base de donnée.", 500)
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

		var args []any
		 
		basicQuery := "SELECT SERVICE.ID_SERVICE, type, description, formation, place, cost, is_medical_confidential, COUNT(OFFER.ID_SERVICE) AS nb FROM SERVICE LEFT JOIN OFFER ON SERVICE.ID_SERVICE = OFFER.ID_SERVICE WHERE 1=1"

		if research != ""{

			basicQuery += " AND (type LIKE CONCAT('%', ?, '%') OR place LIKE CONCAT('%', ?, '%'))"
			args = append(args, research, research)

		}

		if filter != ""{

			basicQuery += " AND (type = ?)"
			args = append(args, filter)

		}

		basicQuery += " GROUP BY SERVICE.ID_SERVICE"

		if sort != ""{

			if sort == "1"{

				basicQuery += " ORDER BY cost ASC"

			}else if sort == "2"{

				basicQuery += " ORDER BY cost DESC"

			}else if sort == "3"{

				basicQuery += " ORDER BY COUNT(OFFER.ID_SERVICE) ASC"

			}else if sort == "4"{

				basicQuery += " ORDER BY COUNT(OFFER.ID_SERVICE) DESC"

			}

		}

		basicQuery += " LIMIT 10 OFFSET ?"
		args = append(args, offset)

		rowsServices, err := database.Query(basicQuery, args...)

		if err != nil {
			http.Error(w, "Erreur lors de la récupération des données depuis la base de données.", 500)
			return 
		}	
		defer rowsServices.Close()

		for rowsServices.Next(){

			var service Service

			err := rowsServices.Scan(&service.ID_SERVICE, &service.Type, &service.Description, &service.Formation, &service.Place, &service.Cost, &service.IsMedicalConfidential, &service.Nb)
			if err != nil {
				continue
			}

			response.Services = append(response.Services, service)
		}
		
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(response)

	}

}