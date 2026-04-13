package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

type Service struct{
	ID_SERVICE int `json:"ID_SERVICE"`
	Type string `json:"type"`
	Description string `json:"description"`
	Place string `json:"place"`
	Cost float64 `json:"cost"`
	IsMedicalConfidential int `json:"is_medical_confidential"`
	Nb int `json:"nb"`
}

type ResponseServices struct {

	Types []string `json:"types"`
	Services []Service `json:"services"`

}

func ShowServicesDefaultData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

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

		rowsServices, err := database.Query("SELECT SERVICE.ID_SERVICE, type, description, place, cost, is_medical_confidential, COUNT(OFFER.ID_SERVICE) AS nb FROM SERVICE LEFT JOIN OFFER ON SERVICE.ID_SERVICE = OFFER.ID_SERVICE GROUP BY SERVICE.ID_SERVICE LIMIT 10 OFFSET ?", offset)
		if err != nil {
			http.Error(w, "Erreur lors de la récupération des données depuis la base de données.", 500)
			return 
		}	
		defer rowsServices.Close()

		for rowsServices.Next(){

			var service Service

			err := rowsServices.Scan(&service.ID_SERVICE, &service.Type, &service.Description, &service.Place, &service.Cost, &service.IsMedicalConfidential, &service.Nb)
			if err != nil {
				continue
			}

			response.Services = append(response.Services, service)
		}
		
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(response)

	}

}