package handlersBack

import (
	"database/sql"
	"net/http"
)

func DeleteService(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")

		deleteStatement, deleteError := database.Prepare("DELETE FROM SERVICE WHERE ID_SERVICE = ?")

		if deleteError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}
		defer deleteStatement.Close()

		_, deleteStatementExecError := deleteStatement.Exec(id)

		if deleteStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=delete_success#pageservices", 303)

	}

}