package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type userDataResponse struct {

	Id string `json:"id"`
	Status string `json:"status"`
	Username string `json:"username"`
	Email string `json:"email"`
	Name string `json:"name"`
	Surname string `json:"surname"`
	City string `json:"city"`
	Street string `json:"street"`
	StreetNumber string `json:"streetNumber"`
	PostalCode string `json:"postalCode"`
	Description string `json:"description"`
	KeyWord1 string `json:"keyWord1"`
	KeyWord2 string `json:"keyWord2"`
	KeyWord3 string `json:"keyWord3"`
	DateInscription string `json:"dateInscription"`
	DarkMode string `json:"darkMode"`
	LevelFont string `json:"levelFont"`
	FontChange string `json:"fontChange"`
	CursorType string `json:"cursorType"`
	Language string `json:"language"`
	ProfilePicture string `json:"profilePicture"`
	TutoSeen string `json:"tutoSeen"`
	Profession string `json:"profession"`
	BirthDate string `json:"birth_date"`
	Banned string `json:"banned"`
	Error string `json:"error"`


}

func GetDataPutInSession(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		token := r.FormValue("token")

		var id string

		var name string
		var surname string
		var username string
		var status string
		var email string
		var description string
		var keyWord1 string
		var keyWord2 string
		var keyWord3 string
		var dateInscription string
		var darkMode string
		var levelFont string
		var fontChange string
		var cursorType string
		var language string
		var profilePicture string
		var city string
		var street string
		var streetNumber string
		var postalCode string
		var birth_date string
		var banned string

		var response userDataResponse

		w.Header().Set("Content-Type", "application/json")

		rowToken := database.QueryRow("SELECT ID_USER FROM TOKEN WHERE token = ?", token)
			
		errToken := rowToken.Scan(&id)

		if errToken != nil {

			response.Error = "Erreur lors du chargement de vos informations."
			json.NewEncoder(w).Encode(response)
			return 

		}

		deleteStatement, deleteError := database.Prepare("DELETE FROM TOKEN WHERE token = ?")

		if deleteError != nil{

			response.Error = "Erreur lors de la suppression du token."
			json.NewEncoder(w).Encode(response)
			return	

		}
		defer deleteStatement.Close()

		_, deleteStatementExecError := deleteStatement.Exec(&token)

		if deleteStatementExecError != nil{

			response.Error = "Erreur lors de la suppression du token."
			json.NewEncoder(w).Encode(response)
			return	

		}

		rowUser := database.QueryRow("SELECT name, surname, username, city, street, nb_street, postal_code, status, email, description, keyword1, keyword2, keyword3, date_inscription, darkMode, levelFont, fontChange, cursorType, language, profilePicture, banned FROM user_ WHERE ID_USER = ?", id)
		
		errUser := rowUser.Scan(&name, &surname, &username, &city, &street, &streetNumber, &postalCode, &status, &email, &description, &keyWord1, &keyWord2, &keyWord3, &dateInscription, &darkMode, &levelFont, &fontChange, &cursorType, &language, &profilePicture, &banned)

		if errUser != nil {

			response.Error = "Erreur lors du chargement de vos informations."
			json.NewEncoder(w).Encode(response)
			return 

		}	

		response.Id = id
		response.Status = status
		response.Name = name
		response.Surname = surname
		response.Username = username
		response.City = city
		response.Street = street
		response.StreetNumber = streetNumber
		response.PostalCode = postalCode
		response.Email = email
		response.Description = description
		response.KeyWord1 = keyWord1
		response.KeyWord2 = keyWord2
		response.KeyWord3 = keyWord3
		response.DateInscription = dateInscription
		response.DarkMode = darkMode
		response.LevelFont = levelFont
		response.FontChange = fontChange
		response.CursorType = cursorType
		response.Language = language
		response.ProfilePicture = profilePicture
		response.Banned = banned

		if(status == "1" || status == "2" || status == "5" || status == "6"){

			var tuto_seen string

			rowUser := database.QueryRow("SELECT birth_date, tuto_seen FROM CONSUMER WHERE ID_USER = ?", id)
		
			errUser := rowUser.Scan(&birth_date, &tuto_seen)

			if errUser != nil {

				response.Error = "Erreur lors du chargement de vos informations."
				json.NewEncoder(w).Encode(response)
				return	

			}

			response.BirthDate = birth_date
			response.TutoSeen = tuto_seen

		}else if(status == "3" || status == "4"){
		
			var profession string

			rowUser := database.QueryRow("SELECT profession FROM SERVICE_PROVIDER WHERE ID_USER = ?", id)
			
			errUser := rowUser.Scan(&profession)

			if errUser != nil {

				response.Error = "Erreur lors du chargement de vos informations."
				json.NewEncoder(w).Encode(response)
				return

			}

			response.Profession = profession

		}

		json.NewEncoder(w).Encode(response)
		 
	}

}