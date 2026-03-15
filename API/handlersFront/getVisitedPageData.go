package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type UserData struct {

	Username string `json:"username"`
	Email string `json:"email"`
	Name string `json:"name"`
	Surname string `json:"surname"`
	Description string `json:"description"`
	KeyWord1 string `json:"keyWord1"`
	KeyWord2 string `json:"keyWord2"`
	KeyWord3 string `json:"keyWord3"`
	Date_inscription string `json:"date_inscription"`
	Last_connection string `json:"last_connection"`
	Profession string `json:"profession"`
	ProfilePicture string `json:"profile_picture"`

}

func GetVisitedPageData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		visitedId := r.FormValue("visitedId")

		var userTab UserData

		rowUser := database.QueryRow("SELECT USER_.username, USER_.email, USER_.name, USER_.surname, USER_.description, USER_.keyWord1, USER_.keyWord2, USER_.keyWord3, USER_.date_inscription, USER_.last_connection, IFNULL(USER_.profilePicture, ''), SERVICE_PROVIDER.profession FROM USER_ JOIN SERVICE_PROVIDER on USER_.ID_USER = SERVICE_PROVIDER.ID_USER WHERE SERVICE_PROVIDER.ID_SERVICE_PROVIDER = ?", visitedId)
	
		errUser := rowUser.Scan(&userTab.Username, &userTab.Email, &userTab.Name, &userTab.Surname, &userTab.Description, &userTab.KeyWord1, &userTab.KeyWord2, &userTab.KeyWord3, &userTab.Date_inscription, &userTab.Last_connection, &userTab.ProfilePicture, &userTab.Profession)

		if errUser != nil {

			w.WriteHeader(500)
			return 

		}

		json.NewEncoder(w).Encode(userTab)

	}

}