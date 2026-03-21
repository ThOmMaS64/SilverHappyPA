package handlersBack

import (
	"database/sql"
	"net/http"
)

func DeleteEvent(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")

		deleteStatement2, deleteError2 := database.Prepare("DELETE FROM PARTICIPATE WHERE ID_EVENT = ?")

		if deleteError2 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageevents", 303)
			return	

		}
		defer deleteStatement2.Close()

		_, deleteStatementExecError2 := deleteStatement2.Exec(id)

		if deleteStatementExecError2 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageevents", 303)
			return	

		}

		deleteStatement3, deleteError3 := database.Prepare("DELETE FROM CONDUCT WHERE ID_EVENT = ?")

		if deleteError3 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageevents", 303)
			return	

		}
		defer deleteStatement3.Close()

		_, deleteStatementExecError3 := deleteStatement3.Exec(id)

		if deleteStatementExecError3 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageevents", 303)
			return	

		}

		deleteStatement, deleteError := database.Prepare("DELETE FROM EVENT WHERE ID_EVENT = ?")

		if deleteError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageevents", 303)
			return	

		}
		defer deleteStatement.Close()

		_, deleteStatementExecError := deleteStatement.Exec(id)

		if deleteStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageevents", 303)
			return	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=delete_success#pageevents", 303)

	}

}