package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type Quote struct{

	IdQuote int `json:"id_quote"`
	Prestation string `json:"prestation"`
	PdfPath string `json:"pdf_path"`
	Status int `json:"status"`

}

type ResponseQuote struct {

	Quotes []Quote `json:"quotes"`
	Error string `json:"error"`

}

func ShowQuotes(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseQuote{
			Quotes: []Quote{},
		}

		id := r.FormValue("id")

		rowSelectQuotes, errSelectQuotes := database.Query("SELECT QUOTE.ID_QUOTE, QUOTE.prestation, QUOTE.pdf_path, QUOTE.status FROM QUOTE INNER JOIN CONSUMER ON QUOTE.ID_CONSUMER = CONSUMER.ID_CONSUMER WHERE CONSUMER.ID_USER = ? ORDER BY QUOTE.ID_QUOTE DESC", id)
	
		if errSelectQuotes != nil{

			w.WriteHeader(500)
			response.Error = "Erreur lors de la récupération des Devis depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}
		defer rowSelectQuotes.Close()

		for rowSelectQuotes.Next(){

			var quote Quote

			err := rowSelectQuotes.Scan(&quote.IdQuote, &quote.Prestation, &quote.PdfPath, &quote.Status)

			if err == nil{

				response.Quotes = append(response.Quotes, quote)

			}
		}

		json.NewEncoder(w).Encode(response)
		 
	}

}