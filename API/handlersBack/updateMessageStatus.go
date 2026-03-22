package handlersBack

import (
	"database/sql"
	"net/http"
)

func UpdateMessageStatus(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")

		updateStatement, updateError := database.Prepare("UPDATE MESSAGE SET suspected_status = ? WHERE ID_MESSAGE = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pagemessages", 303)
			return 

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(2, id)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pagemessages", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=update_success#pagemessages", 303)

	}

}