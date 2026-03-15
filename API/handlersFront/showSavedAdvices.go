package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)




func ShowSavedAdvices(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := Response{
			Themes: []string{},
			Advices: []Advice{},
		}

		userId := r.FormValue("id")

		rowSelectAdvices, errSelectAdvices := database.Query("SELECT ADVICE.ID_ADVICE, ADVICE.description, SERVICE_PROVIDER.profession, USER_.username FROM ADVICE JOIN SERVICE_PROVIDER ON ADVICE.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER JOIN USER_INTERACTION_ADVICE ON ADVICE.ID_ADVICE = USER_INTERACTION_ADVICE.ID_ADVICE WHERE USER_INTERACTION_ADVICE.ID_USER = ?", userId)
	
		if errSelectAdvices != nil{

			w.WriteHeader(500)
			response.Error = "Erreur lors de la récupération des Conseils depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectAdvices.Close()

		for rowSelectAdvices.Next(){

			var advice Advice

			err := rowSelectAdvices.Scan(&advice.IdAdvice, &advice.Description, &advice.Profession, &advice.Username)

			if err == nil{

				response.Advices = append(response.Advices, advice)

			}
		}

		json.NewEncoder(w).Encode(response)
		 
	}

}