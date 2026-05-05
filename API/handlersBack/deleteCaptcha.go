package handlersBack

import (
	"database/sql"
	"net/http"
)

func DeleteCaptcha(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")

		deleteStatement, deleteError := database.Prepare("DELETE FROM CAPTCHA WHERE ID_CAPTCHA = ?")

		if deleteError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pagecaptcha", 303)
			return

		}
		defer deleteStatement.Close()

		_, deleteExecError := deleteStatement.Exec(id)

		if deleteExecError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pagecaptcha", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=delete_success#pagecaptcha", 303)

	}

}