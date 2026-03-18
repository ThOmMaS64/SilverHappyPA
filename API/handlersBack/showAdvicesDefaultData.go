package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

type Advice struct{
	ID_ADVICE int `json:"ID_ADVICE"`
	Title string `json:"title"`
	Theme string `json:"theme"`
	Description string `json:"description"`
	Date_publication string `json:"date_publication"`
	Auteur string `json:"auteur"`
}

type Response struct {

	Themes []string `json:"themes"`
	Advices []Advice `json:"advices"`

}

func ShowAdvicesDefaultData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

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

		rowsAdvices, err := database.Query("SELECT ADVICE.ID_ADVICE, ADVICE.title, ADVICE.theme, ADVICE.description, ADVICE.date_publication, USER_.username FROM ADVICE INNER JOIN SERVICE_PROVIDER ON ADVICE.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER LIMIT 10 OFFSET ?", offset)
		if err != nil {
			http.Error(w, err.Error(), 500)
			return 
		}	
		defer rowsAdvices.Close()

		var advices []Advice
		
		for rowsAdvices.Next(){

			var advice Advice

			err := rowsAdvices.Scan(&advice.ID_ADVICE, &advice.Title, &advice.Theme, &advice.Description, &advice.Date_publication, &advice.Auteur)	
			if err != nil {
				continue
			}

			advices = append(advices, advice)
		}
		
		w.Header().Set("Content-Type", "application/json")
		response.Advices = advices
		json.NewEncoder(w).Encode(response)

	}

}