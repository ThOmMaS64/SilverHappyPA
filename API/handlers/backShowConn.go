package handlers

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type UserConn struct{
	ID_USER int `json:"ID_USER"`
	Username string `json:"username"`
	Email string `json:"email"`
	Connected string `json:"connected"`
}

func BackShowConn(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		rowsUsers, err := database.Query("SELECT ID_USER, username, email, connected FROM user_ LIMIT 10")
		if err != nil {
			http.Error(w, err.Error(), 500)
			return 
		}	
		defer rowsUsers.Close()

		var users []UserConn
		

		for rowsUsers.Next(){

			var user UserConn

			err := rowsUsers.Scan(&user.ID_USER, &user.Username, &user.Email, &user.Connected)	
			if err != nil {
				continue
			}

			users = append(users, user)
		}
		
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(users)

	}

}