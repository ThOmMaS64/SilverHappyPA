package handlers

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ProfilePictureResponse struct{

	ProfilePicture string `json:"profilePicture"`
	Error string `json:"error"`

}

type KeywordDescriptionResponse struct{

	KeyWord1 string `json:"keyWord1"`
	KeyWord2 string `json:"keyWord2"`
	KeyWord3 string `json:"keyWord3"`
	Description string `json:"description"`
	Error string `json:"error"`

}

func ShowUpdatedData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		userId := r.FormValue("id")
		ask := r.FormValue("ask")

		if ask == "1" {

			var profilePicture string
			var response ProfilePictureResponse

			rowUser := database.QueryRow("SELECT profilePicture FROM user_ WHERE ID_USER = ?", userId)
		
			errUser := rowUser.Scan(&profilePicture)

			if errUser != nil {

				response.Error = "Erreur lors du changement de la photo de profil, votre photo de profil se mettra à jour à votre prochaine connexion."
				json.NewEncoder(w).Encode(response)
				return 

			}	

			response.ProfilePicture = profilePicture
			json.NewEncoder(w).Encode(response)

		}else if ask == "2"{

			var description string
			var keyword1 string
			var keyword2 string
			var keyword3 string

			var response KeywordDescriptionResponse

			rowUser := database.QueryRow("SELECT description, keyWord1, keyWord2, keyWord3 FROM user_ WHERE ID_USER = ?", userId)
		
			errUser := rowUser.Scan(&description, &keyword1, &keyword2, &keyword3)

			if errUser != nil {

				response.Error = "Erreur lors du changement de vos informations, vos informations se mettrons à jour à votre prochaine connexion."
				json.NewEncoder(w).Encode(response)
				return 

			}	

			response.Description = description
			response.KeyWord1 = keyword1
			response.KeyWord2 = keyword2
			response.KeyWord3 = keyword3

			json.NewEncoder(w).Encode(response)

		}
		 
	}

}