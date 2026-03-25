package handlersBack

import (
	"database/sql"
	"net/http"
)

func UpdateNotificationData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		title:= r.FormValue("title")
		typenotif:= r.FormValue("type")
		description:= r.FormValue("description")

		updateStatement, updateError := database.Prepare("UPDATE NOTIFICATION SET title = ?, type = ?, description = ? WHERE ID_NOTIFICATION = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pagenotifs", 303)
			return 

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(title, typenotif, description, id)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pagenotifs", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=update_success#pagenotifs", 303)

	}

}