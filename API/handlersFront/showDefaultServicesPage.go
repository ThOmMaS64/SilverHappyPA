package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type Service struct {

	ID_SERVICE int `json:"ID_SERVICE"`
	Type string `json:"type"`
	Description string `json:"description"`
	Formation string `json:"formation"`
	Place string `json:"place"`
	Cost float64 `json:"cost"`
	IsMedicalConfidential string `json:"is_medical_confidential"`
	IsSaved bool `json:"is_saved"`
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
		

		rowSelectServices, errSelectServices := database.Query("SELECT SERVICE.ID_SERVICE, SERVICE.type, SERVICE.description, SERVICE.formation, SERVICE.place, SERVICE.cost, SERVICE.is_medical_confidential, (USER_INTERACTION_SERVICE.ID_USER IS NOT NULL) AS is_saved FROM SERVICE LEFT JOIN USER_INTERACTION_SERVICE ON SERVICE.ID_SERVICE = USER_INTERACTION_SERVICE.ID_SERVICE AND USER_INTERACTION_SERVICE.ID_USER = ?", id)
	
		if errSelectServices != nil{

			w.WriteHeader(500)
			response.Error = "Erreur lors de la récupération des prestations depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectServices.Close()

		for rowSelectServices.Next(){

			var service Service

			err := rowSelectServices.Scan(&service.ID_SERVICE, &service.Type, &service.Description, &service.Formation, &service.Place, &service.Cost, &service.IsMedicalConfidential, &service.IsSaved)

			if err == nil{

				response.Services = append(response.Services, service)

			}
		}

		json.NewEncoder(w).Encode(response)
		 
	}

}