package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type Message struct {
	ID_USER int `json:"id_user"`
	Name string `json:"name"`
	Surname string `json:"surname"`
	Content string `json:"content"`
	Date string `json:"date"`
}

func ShowMessages(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idDiscussion := r.FormValue("id_discussion")

		rows, errRows := database.Query("SELECT MESSAGE.content, MESSAGE.date, USER_.ID_USER, USER_.name, USER_.surname FROM MESSAGE INNER JOIN USER_ ON MESSAGE.sender_id = USER_.ID_USER WHERE MESSAGE.ID_DISCUSSION = ? ORDER BY MESSAGE.date ASC", idDiscussion)
	
		if errRows != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/messaging.php?error=system1", 303)
			return

		}
		defer rows.Close()

		messages := []Message{}

		for rows.Next() {

			var message Message

			 err := rows.Scan(&message.Content, &message.Date, &message.ID_USER, &message.Name, &message.Surname)

			if err != nil {
				continue
			}
			messages = append(messages, message)
		}

		json.NewEncoder(w).Encode(messages)
	}

}