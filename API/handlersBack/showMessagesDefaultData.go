package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"os"
	"strconv"
	"strings"
)

type Message struct{
	ID_MESSAGE int `json:"ID_MESSAGE"`
	Date string `json:"date"`
	Content string `json:"content"`
	Sender string `json:"sender"`
	SuspectedStatus int `json:"suspected_status"`
}

type MessageResponse struct {
	Messages []Message `json:"messages"`
}

func ShowMessagesDefaultData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		offsetString := r.FormValue("offset")
		offset := 0

		if offsetString != ""{

			offset, _ = strconv.Atoi(offsetString)

		}

		file, _ := os.ReadFile("../../backoffice/blacklist.txt")
		blacklist := strings.Split(strings.ToLower(string(file)), "\n")

		rowsMessage, err := database.Query("SELECT ID_MESSAGE, date, content, suspected_status, username FROM MESSAGE INNER JOIN USER_ ON MESSAGE.sender_id = USER_.ID_USER")
		if err != nil {
			http.Error(w, "Erreur lors de la récupération des données depuis la base de données.", 500)
			return 
		}	
		defer rowsMessage.Close()

		var response MessageResponse
		response.Messages = make([]Message, 0)

		skipped := 0

		for rowsMessage.Next(){

			var message Message

			err := rowsMessage.Scan(&message.ID_MESSAGE, &message.Date, &message.Content, &message.SuspectedStatus, &message.Sender)
			if err != nil {
				continue
			}

			var found bool

			if message.SuspectedStatus == 2{

				found = false

			}else if message.SuspectedStatus == 1{

				found = true

			}else{

				content := strings.ToLower(message.Content)
				found = false

				for _, badWord := range blacklist {	
					badWord = strings.TrimSpace(badWord)			
					if badWord != "" && strings.Contains(content, badWord) {
						found = true
						break
					}
				}

			}

			if found {

				if skipped < offset{

					skipped++
					continue

				}

				if message.SuspectedStatus == 0 {
					_, err := database.Exec("UPDATE MESSAGE SET suspected_status = 1 WHERE ID_MESSAGE = ?", message.ID_MESSAGE)
					if err == nil {
						message.SuspectedStatus = 1
					}
				}
				
				response.Messages = append(response.Messages, message)
			}

			if len(response.Messages) >= 10 {
				break
			}
			
		}
		
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(response)

	}

}