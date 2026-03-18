package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

func ShowAdvicesPersonalizedData(database *sql.DB) http.HandlerFunc {

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

		response := Response{
			Themes: []string{},
			Advices: []Advice{},
		}

		rowSelectTheme, errSelectTheme := database.Query("SELECT DISTINCT theme FROM ADVICE ORDER BY theme ASC")
	
		if errSelectTheme != nil {

			http.Error(w, "Erreur lors de la récupération des Thèmes depuis la base de donnée.", 500)
			return 

		}

		defer rowSelectTheme.Close()

		for rowSelectTheme.Next(){

			var theme string

			err := rowSelectTheme.Scan(&theme)

			if err == nil{

				response.Themes = append(response.Themes, theme)

			}

		}

		var args []any
		 
		basicQuery := "SELECT ADVICE.ID_ADVICE, ADVICE.title, ADVICE.theme, ADVICE.description, ADVICE.date_publication, USER_.username FROM ADVICE INNER JOIN SERVICE_PROVIDER ON ADVICE.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER WHERE 1=1"

		if research != ""{

			basicQuery += " AND (ADVICE.title LIKE CONCAT('%', ?, '%') OR ADVICE.theme LIKE CONCAT('%', ?, '%') OR ADVICE.description LIKE CONCAT('%', ?, '%') OR USER_.username LIKE CONCAT('%', ?, '%'))"
			args = append(args, research, research, research, research)

		}

		if filter != ""{

			basicQuery += " AND (ADVICE.theme = ?)"
			args = append(args, filter)

		}

		if sort != ""{

			if sort == "1"{

				basicQuery += " ORDER BY ADVICE.date_publication ASC"

			}else if sort == "2"{

				basicQuery += " ORDER BY ADVICE.date_publication DESC"

			}else if sort == "3"{

				basicQuery += " ORDER BY (SELECT COUNT(*) FROM USER_INTERACTION_ADVICE WHERE USER_INTERACTION_ADVICE.ID_ADVICE = ADVICE.ID_ADVICE) DESC"

			}else if sort == "4"{

				basicQuery += " ORDER BY (SELECT COUNT(*) FROM USER_INTERACTION_ADVICE WHERE USER_INTERACTION_ADVICE.ID_ADVICE = ADVICE.ID_ADVICE) ASC"

			}

		}

		basicQuery += " LIMIT 10 OFFSET ?"
		args = append(args, offset)

		rowSelectUser, errSelectUser := database.Query(basicQuery, args...)
	
		if errSelectUser != nil{

			http.Error(w, "Erreur lors de la récupération des Conseils depuis la base de donnée.", 500)
			return 

		}
		defer rowSelectUser.Close()

		for rowSelectUser.Next(){

			var advice Advice

			err := rowSelectUser.Scan(&advice.ID_ADVICE, &advice.Title, &advice.Theme, &advice.Description, &advice.Date_publication, &advice.Auteur)

			if err == nil{

				response.Advices = append(response.Advices, advice)

			}
		}

		json.NewEncoder(w).Encode(response)

	}

}