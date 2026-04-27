package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ResponseNeededDocuments struct{

	Documents []string `json:"documents"`
	Error string `json:"error"`

}

func ShowNeededDocuments(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseNeededDocuments{

			Documents: []string{},

		}

		serviceType := r.FormValue("service")

		query := "SELECT REQUIRED_DOCUMENT.name FROM REQUIRED_DOCUMENT JOIN SERVICE_DOCUMENT  ON REQUIRED_DOCUMENT.ID_REQUIRED_DOCUMENT = SERVICE_DOCUMENT.ID_REQUIRED_DOCUMENT JOIN SERVICE ON SERVICE_DOCUMENT.ID_SERVICE = SERVICE.ID_SERVICE WHERE SERVICE.type = ? ORDER BY REQUIRED_DOCUMENT.name ASC"

		rowsSelectDocs, errSelectDocs := database.Query(query, serviceType)
	
		if errSelectDocs != nil {

			response.Error = "Erreur lors de la récupération des documents depuis la base de données."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowsSelectDocs.Close()

		for rowsSelectDocs.Next(){

			var document string

			err := rowsSelectDocs.Scan(&document)

			if err == nil{

				response.Documents = append(response.Documents, document)

			}

		}
		
		json.NewEncoder(w).Encode(response)
		 
	}

}