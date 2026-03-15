package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type User struct{
	ID_USER int `json:"ID_USER"`
	Username string `json:"username"`
	Name string `json:"name"`
	Surname string `json:"surname"`
	Description string `json:"description"`
	KeyWord1 string `json:"keyWord1"`
	KeyWord2 string `json:"keyWord2"`
	KeyWord3 string `json:"keyWord3"`
	Email string `json:"email"`
	City string `json:"city"`
	Street string `json:"street"`
	Nb_street string `json:"nb_street"`
	Postal_code string `json:"postal_code"`
	Status int `json:"status"`
	Connected int `json:"connected"`
	LastConnection string `json:"lastConnection"`
	BirthDate string `json:"birthDate"`
	DateInscription string `json:"date_inscription"`
}

func ShowUsersDefaultData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		users := []User{}

		rowsUsers, err := database.Query("SELECT USER_.ID_USER, COALESCE(USER_.username, ''), COALESCE(USER_.name, ''), COALESCE(USER_.surname, ''), COALESCE(USER_.description, ''), COALESCE(USER_.keyWord1, ''), COALESCE(USER_.keyWord2, ''), COALESCE(USER_.keyWord3, ''), COALESCE(USER_.email, ''), COALESCE(USER_.city, ''), COALESCE(USER_.street, ''), COALESCE(USER_.nb_street, ''), COALESCE(USER_.postal_code, ''), COALESCE(USER_.status, 0), COALESCE(USER_.connected, 0), COALESCE(USER_.last_connection, '1900-01-01 00:00:00'), COALESCE(USER_.date_inscription, '1900-01-01'), COALESCE(CONSUMER.birth_date, '1900-01-01') FROM USER_ LEFT JOIN CONSUMER ON USER_.ID_USER = CONSUMER.ID_USER LIMIT 10")
		
		if err != nil {
		
			http.Error(w, "Erreur lors de la récupération des données depuis la base de données.", 500)
			return 

		}	
		defer rowsUsers.Close()
		
		for rowsUsers.Next(){

			var user User

			err := rowsUsers.Scan(&user.ID_USER, &user.Username, &user.Name, &user.Surname, &user.Description, &user.KeyWord1, &user.KeyWord2, &user.KeyWord3, &user.Email, &user.City, &user.Street, &user.Nb_street, &user.Postal_code, &user.Status, &user.Connected, &user.LastConnection, &user.DateInscription, &user.BirthDate)	
			if err != nil {
				continue
			}

			users = append(users, user)
		}
		
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(users)

	}

}