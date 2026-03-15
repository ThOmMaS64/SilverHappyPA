package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type Advice struct {

	IdAdvice int `json:"id_advice"`
	Title string `json:"title"`
	Theme string `json:"theme"`
	Description string `json:"description"`
	DatePublication string `json:"date_publication"`
	IdServiceProvider string `json:"id_service_provider"`
	Profession string `json:"profession"`
	Name string `json:"name"`
	Surname string `json:"surname"`
	Username string `json:"username"`
	IsSaved bool `json:"is_saved"`

}

type Response struct {

	Themes []string `json:"themes"`
	Advices []Advice `json:"advices"`
	Error string `json:"error"`

}

func ShowDefaultAdvicesPage(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := Response{
			Themes: []string{},
			Advices: []Advice{},
		}

		userId := r.FormValue("id")

		rowSelectTheme, errSelectTheme := database.Query("SELECT DISTINCT theme FROM ADVICE ORDER BY theme ASC")
	
		if errSelectTheme != nil {

			response.Error = "Erreur lors de la récupération des Thèmes depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
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
		

		rowSelectAdvices, errSelectAdvices := database.Query("SELECT ADVICE.ID_ADVICE, ADVICE.title, ADVICE.theme, ADVICE.description, ADVICE.date_publication, SERVICE_PROVIDER.ID_SERVICE_PROVIDER, SERVICE_PROVIDER.profession, USER_.name, USER_.surname, USER_.username, (USER_INTERACTION_ADVICE.ID_USER IS NOT NULL) AS is_saved FROM ADVICE JOIN SERVICE_PROVIDER ON ADVICE.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER LEFT JOIN USER_INTERACTION_ADVICE ON ADVICE.ID_ADVICE = USER_INTERACTION_ADVICE.ID_ADVICE AND USER_INTERACTION_ADVICE.ID_USER = ?", userId)
	
		if errSelectAdvices != nil{

			w.WriteHeader(500)
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