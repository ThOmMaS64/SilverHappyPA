package handlers

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

func ShowPersonalizedAdvicesPage(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		research := r.FormValue("research")
		filter := r.FormValue("filter")
		sort := r.FormValue("sort")

		response := Response{
			Advices: []Advice{},
		}

		userId := r.FormValue("id")

		var args []any
		 
		basicQuery := "SELECT ADVICE.ID_ADVICE, ADVICE.title, ADVICE.theme, ADVICE.description, ADVICE.date_publication, SERVICE_PROVIDER.ID_SERVICE_PROVIDER, SERVICE_PROVIDER.profession, USER_.name, USER_.surname, USER_.username, (USER_INTERACTION_ADVICE.ID_USER IS NOT NULL) AS is_saved FROM ADVICE JOIN SERVICE_PROVIDER ON ADVICE.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER LEFT JOIN USER_INTERACTION_ADVICE ON ADVICE.ID_ADVICE = USER_INTERACTION_ADVICE.ID_ADVICE AND USER_INTERACTION_ADVICE.ID_USER = ? WHERE 1=1"
		args = append(args, userId)

		if research != ""{

			basicQuery += " AND (ADVICE.title LIKE CONCAT('%', ?, '%') OR ADVICE.theme LIKE CONCAT('%', ?, '%') OR ADVICE.description LIKE CONCAT('%', ?, '%'))"
			args = append(args, research, research, research)

		}

		if filter != ""{

			basicQuery += " AND ADVICE.theme = ?"
			args = append(args, filter)

		}

		if sort == "1"{

			basicQuery += " ORDER BY ADVICE.date_publication DESC"

		}else if sort == "2"{

			basicQuery += " ORDER BY ADVICE.date_publication ASC"

		}else if sort =="3"{

			rowSelectThemes, errSelecThemes := database.Query("SELECT ADVICE.theme FROM ADVICE JOIN USER_INTERACTION_ADVICE ON ADVICE.ID_ADVICE = USER_INTERACTION_ADVICE.ID_ADVICE WHERE USER_INTERACTION_ADVICE.ID_USER = ?", userId)
	
			if errSelecThemes != nil{

				w.WriteHeader(500)
				response.Error = "Erreur lors de la récupération des Conseils depuis la base de donnée."
				json.NewEncoder(w).Encode(response)
				return 

			}

			defer rowSelectThemes.Close()

			var themes []string

			for rowSelectThemes.Next(){

				var theme string

				err := rowSelectThemes.Scan(&theme)

				if err == nil{

					themes = append(themes, theme)

				}
			}

			if len(themes) > 0 {

				basicQuery += " ORDER BY ("
				
				for i:=0; i<len(themes); i++{

					if i != 0 {
						
						basicQuery += " OR "

					}

					basicQuery += "ADVICE.theme = ?"
					args = append(args, themes[i])

				}

				basicQuery += ") DESC"

			}

		}

		rowSelectAdvices, errSelectAdvices := database.Query(basicQuery, args...)
	
		if errSelectAdvices != nil{

			response.Error = "Erreur lors de la récupération des Conseils depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectAdvices.Close()

		for rowSelectAdvices.Next(){

			var advice Advice

			err := rowSelectAdvices.Scan(&advice.IdAdvice, &advice.Title, &advice.Theme, &advice.Description, &advice.DatePublication, &advice.IdServiceProvider, &advice.Profession, &advice.Name, &advice.Surname, &advice.Username, &advice.IsSaved)

			if err == nil{

				response.Advices = append(response.Advices, advice)

			}
		}

		json.NewEncoder(w).Encode(response)

	}

}