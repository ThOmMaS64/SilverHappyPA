package handlersFront

import (
	"database/sql"
	"fmt"
	"net/http"
	"strings"
)

func SendMessage(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		idDiscussion := r.FormValue("id_discussion")
		idService := r.FormValue("id_service")
		content := r.FormValue("content")

		url := fmt.Sprintf("http://localhost/ProjetAnnuel/messaging.php?id_discussion=%s&id_service=%s", idDiscussion, idService)

		if strings.TrimSpace(content) == ""{

			http.Redirect(w, r, url, 303)
			return

		}

		insertStatement, insertError := database.Prepare("INSERT INTO MESSAGE(content, date, sender_id, ID_DISCUSSION) VALUES(?, NOW(), ?, ?)")

		if insertError != nil {

			http.Redirect(w, r, url + "&error=sending_error", 303)
			return

		}
		defer insertStatement.Close()

		_, insertExecError := insertStatement.Exec(content, id, idDiscussion)

		if insertExecError != nil {

			http.Redirect(w, r, url + "&error=sending_error", 303)
			return

		}

		http.Redirect(w, r, url, 303)
		return
		 
	}

}