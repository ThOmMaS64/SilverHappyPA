package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ResponseSingleServices struct{

	Services []string `json:"services"`
	Error string `json:"error"`

}

func ShowServices(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseSingleServices{

			Services: []string{},

		}

		rowSelectTheme, errSelectTheme := database.Query("SELECT DISTINCT type FROM SERVICE ORDER BY type ASC")
	
		if errSelectTheme != nil {

			response.Error = "Erreur lors de la récupération des Services depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectTheme.Close()

		for rowSelectTheme.Next(){

			var service string

			err := rowSelectTheme.Scan(&service)

			if err == nil{

				response.Services = append(response.Services, service)

			}

		}
		
		json.NewEncoder(w).Encode(response)
		 
	}

}