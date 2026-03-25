package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

func ShowNotificationsPersonalizedData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		research := r.FormValue("research")
		filter := r.FormValue("filter")
		sort := r.FormValue("sort")
		
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

		var args []any
		 
		basicQuery := "SELECT NOTIFICATION.ID_NOTIFICATION, NOTIFICATION.title, NOTIFICATION.description, NOTIFICATION.type, USER_.username, NOTIFICATION.ID_CONSUMER FROM NOTIFICATION INNER JOIN CONSUMER ON NOTIFICATION.ID_CONSUMER = CONSUMER.ID_CONSUMER INNER JOIN USER_ ON CONSUMER.ID_USER = USER_.ID_USER WHERE 1=1"

		if research != ""{

			basicQuery += " AND (NOTIFICATION.title LIKE CONCAT('%', ?, '%') OR NOTIFICATION.type LIKE CONCAT('%', ?, '%') OR NOTIFICATION.description LIKE CONCAT('%', ?, '%') OR USER_.username LIKE CONCAT('%', ?, '%'))"
			args = append(args, research, research, research, research)

		}

		if filter != ""{

			basicQuery += " AND (NOTIFICATION.type = ?)"
			args = append(args, filter)

		}

		if sort != ""{

		}

		basicQuery += " LIMIT 10 OFFSET ?"
		args = append(args, offset)

		rowSelectNotifs, errSelectNotifs := database.Query(basicQuery, args...)
	
		if errSelectNotifs != nil{

			http.Error(w, "Erreur lors de la récupération des notifications depuis la base de donnée.", 500)
			return 

		}
		defer rowSelectNotifs.Close()

		for rowSelectNotifs.Next(){

			var notif Notification

			err := rowSelectNotifs.Scan(&notif.ID_NOTIFICATION, &notif.Title, &notif.Description, &notif.Type, &notif.Username, &notif.ID_CONSUMER)

			if err == nil{

				response.Notifs = append(response.Notifs, notif)

			}
		}

		json.NewEncoder(w).Encode(response)

	}

}