package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

type Request struct{
	ID_REQUEST int `json:"ID_REQUEST"`
	Subject string `json:"subject"`
	Request string `json:"request"`
	Email string `json:"email"`
	Date string `json:"date"`
}

type ResponseRequest struct {

	Subjects []string `json:"subjects"`
	Requests []Request `json:"requests"`

}

func ShowRequestsDefaultData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		offsetString := r.FormValue("offset")
		offset := 0

		if offsetString != ""{

			offset, _ = strconv.Atoi(offsetString)

		}

		response := ResponseRequest{
			Subjects: []string{},
			Requests: []Request{},
		}

		rowSelectTheme, errSelectTheme := database.Query("SELECT DISTINCT subject FROM CONTACTS ORDER BY subject ASC")
	
		if errSelectTheme != nil {

			http.Error(w, "Erreur lors de la récupération des Sujets depuis la base de donnée.", 500)
			return 

		}

		defer rowSelectTheme.Close()

		for rowSelectTheme.Next(){

			var subject string

			err := rowSelectTheme.Scan(&subject)

			if err == nil{

				response.Subjects = append(response.Subjects, subject)

			}

		}

		rowsRequests, err := database.Query("SELECT CONTACTS.id, COALESCE(CONTACTS.subject, ''), COALESCE(CONTACTS.message, ''), COALESCE(CONTACTS.email, ''), COALESCE(CONTACTS.date, '') FROM CONTACTS LIMIT 10 OFFSET ?", offset)		
		
		if err != nil {
			http.Error(w, "Erreur lors de la récupération des Requêtes depuis la base de donnée.", 500)
			return 
		}	
		defer rowsRequests.Close()

		var requests []Request
		
		for rowsRequests.Next(){

			var request Request

			err := rowsRequests.Scan(&request.ID_REQUEST, &request.Subject, &request.Request, &request.Email, &request.Date)	
			if err != nil {
				continue
			}

			requests = append(requests, request)
		}
		
		w.Header().Set("Content-Type", "application/json")
		response.Requests = requests
		json.NewEncoder(w).Encode(response)

	}

}