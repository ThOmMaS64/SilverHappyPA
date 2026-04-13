package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

func ShowSavedService(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseService{
			Types: []string{},
			Services: []Service{},
		}

		id := r.FormValue("id")

		rowSelectServices, errSelectServices := database.Query("SELECT SERVICE.ID_SERVICE, SERVICE.type, SERVICE.description, SERVICE.place, SERVICE.cost, SERVICE.is_medical_confidential FROM SERVICE JOIN USER_INTERACTION_SERVICE ON SERVICE.ID_SERVICE = USER_INTERACTION_SERVICE.ID_SERVICE WHERE USER_INTERACTION_SERVICE.ID_USER = ?", id)
	
		if errSelectServices != nil{

			w.WriteHeader(500)
			response.Error = "Erreur lors de la récupération des prestations depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectServices.Close()

		for rowSelectServices.Next(){

			var service Service

			err := rowSelectServices.Scan(&service.ID_SERVICE, &service.Type, &service.Description, &service.Place, &service.Cost, &service.IsMedicalConfidential)

			if err == nil{

				response.Services = append(response.Services, service)

			}
		}

		json.NewEncoder(w).Encode(response)
		 
	}

}