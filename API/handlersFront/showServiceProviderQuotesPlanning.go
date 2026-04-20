package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ServiceProviderQuote struct{

	Prestation string `json:"prestation"`
	Amount string `json:"amount"`
	Content string `json:"content"`
	DateStartOrUnique string `json:"date_start_or_unique"`
	DateEnd string `json:"date_end"`
	DatePersonalized string `json:"date_personalized"`

}

type ResponseServiceProviderCalendarQuotes struct{

	Quotes []ServiceProviderQuote `json:"quotes"`
	Error string `json:"error"`

}

func ShowServiceProviderQuotesPlanning(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseServiceProviderCalendarQuotes{
			Quotes: []ServiceProviderQuote{},
		}

		id := r.FormValue("id")

		rowSelectServices, errSelectServices := database.Query("SELECT COALESCE(prestation, ''), COALESCE(amount, 0), COALESCE(content, ''), COALESCE(date_start_or_unique, ''), COALESCE(date_end, ''), COALESCE(date_personalized, '') FROM QUOTE WHERE ID_SERVICE_PROVIDER = (SELECT ID_SERVICE_PROVIDER FROM SERVICE_PROVIDER WHERE ID_USER = ?) AND status = 2", id)
	
		if errSelectServices != nil{

			w.WriteHeader(500)
			response.Error = "Erreur lors de la récupération des Services depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}
		defer rowSelectServices.Close()

		for rowSelectServices.Next(){

			var service ServiceProviderQuote

			err := rowSelectServices.Scan(&service.Prestation, &service.Amount, &service.Content, &service.DateStartOrUnique, &service.DateEnd, &service.DatePersonalized)

			if err == nil{

				response.Quotes = append(response.Quotes, service)

			}
		}

		json.NewEncoder(w).Encode(response)
		 
	}

}