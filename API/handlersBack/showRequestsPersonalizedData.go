package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

func ShowRequestsPersonalizedData(database *sql.DB) http.HandlerFunc {

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

		response := ResponseRequest{
			Subjects: []string{},
			Requests: []Request{},
		}

		rowSelectTheme, errSelectTheme := database.Query("SELECT DISTINCT subject FROM CONTACTS ORDER BY subject ASC")
	
		if errSelectTheme != nil {

			http.Error(w, "Erreur lors de la récupération des sujets depuis la base de donnée.", 500)
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

		var args []any
		 
		basicQuery := "SELECT CONTACTS.id, CONTACTS.date, CONTACTS.subject, CONTACTS.message, CONTACTS.email FROM CONTACTS WHERE 1=1"

		if research != ""{

			basicQuery += " AND (CONTACTS.subject LIKE CONCAT('%', ?, '%') OR CONTACTS.message LIKE CONCAT('%', ?, '%') OR CONTACTS.email LIKE CONCAT('%', ?, '%'))"
			args = append(args, research, research, research)

		}

		if filter != ""{

			basicQuery += " AND (CONTACTS.subject = ?)"
			args = append(args, filter)

		}

		if sort != ""{

			if sort == "1"{

				basicQuery += " ORDER BY CONTACTS.date ASC"

			}else if sort == "2"{

				basicQuery += " ORDER BY CONTACTS.date DESC"

			}
		}

		basicQuery += " LIMIT 10 OFFSET ?"
		args = append(args, offset)

		rowSelectUser, errSelectUser := database.Query(basicQuery, args...)
	
		if errSelectUser != nil{

			http.Error(w, "Erreur lors de la récupération des requêtes depuis la base de donnée.", 500)
			return 

		}
		defer rowSelectUser.Close()

		for rowSelectUser.Next(){

			var request Request

			err := rowSelectUser.Scan(&request.ID_REQUEST, &request.Date, &request.Subject, &request.Request, &request.Email)

			if err == nil{

				response.Requests = append(response.Requests, request)

			}
		}

		json.NewEncoder(w).Encode(response)

	}

}