package handlersBack

import (
	"database/sql"
	"net/http"
)

func DeleteNotification(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")

		updateStatement, updateError := database.Prepare("DELETE FROM NOTIFICATION WHERE ID_NOTIFICATION = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=delete_error#pagenotifs", 303)
			return	

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(id)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=delete_error#pagenotifs", 303)
			return	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=delete_success#pagenotifs", 303)

	}

}