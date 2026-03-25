package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

type Notification struct{
	ID_NOTIFICATION int `json:"ID_NOTIFICATION"`
	Title string `json:"title"`
	Description string `json:"description"`
	Type string `json:"type"`
	Username string `json:"username"`
	ID_CONSUMER string `json:"ID_CONSUMER"`
}

type ResponseNotifs struct {

	Types []string `json:"types"`
	Notifs []Notification `json:"notifs"`

}

func ShowNotificationsDefaultData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		offsetString := r.FormValue("offset")
		offset := 0

		if offsetString != ""{

			offset, _ = strconv.Atoi(offsetString)

		}

		response := ResponseNotifs{
			Types: []string{},
			Notifs: []Notification{},
		}

		rowSelectType, errSelectType := database.Query("SELECT DISTINCT type FROM NOTIFICATION ORDER BY type ASC")
	
		if errSelectType != nil {

			http.Error(w, "Erreur lors de la récupération des types depuis la base de donnée.", 500)
			return 

		}

		defer rowSelectType.Close()

		for rowSelectType.Next(){

			var typenotif string

			err := rowSelectType.Scan(&typenotif)

			if err == nil{

				response.Types = append(response.Types, typenotif)

			}

		}

		rowsNotifs, err := database.Query("SELECT NOTIFICATION.ID_NOTIFICATION, NOTIFICATION.title, NOTIFICATION.description, NOTIFICATION.type, USER_.username, NOTIFICATION.ID_CONSUMER FROM NOTIFICATION INNER JOIN CONSUMER ON NOTIFICATION.ID_CONSUMER = CONSUMER.ID_CONSUMER INNER JOIN USER_ ON CONSUMER.ID_USER = USER_.ID_USER LIMIT 10 OFFSET ?", offset)
		if err != nil {
			http.Error(w, err.Error(), 500)
			return 
		}	
		defer rowsNotifs.Close()

		var notifs []Notification
		
		for rowsNotifs.Next(){

			var notif Notification

			err := rowsNotifs.Scan(&notif.ID_NOTIFICATION, &notif.Title, &notif.Description, &notif.Type, &notif.Username, &notif.ID_CONSUMER)	
			if err != nil {
				continue
			}

			notifs = append(notifs, notif)
		}
		
		w.Header().Set("Content-Type", "application/json")
		response.Notifs = notifs
		json.NewEncoder(w).Encode(response)

	}

}