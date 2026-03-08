package handlers

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
	Email string `json:"email"`
	Status int `json:"status"`
}

func BackShowUsers(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		rowsUsers, err := database.Query("SELECT ID_USER, username, name, surname, description, email, status FROM user_ LIMIT 10")
		if err != nil {
			http.Error(w, err.Error(), 500)
			return 
		}	
		defer rowsUsers.Close()

		var users []User
		

		for rowsUsers.Next(){

			var user User

			err := rowsUsers.Scan(&user.ID_USER, &user.Username, &user.Name, &user.Surname, &user.Description, &user.Email, &user.Status)	
			if err != nil {
				continue
			}

			users = append(users, user)
		}
		
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(users)

	}

}