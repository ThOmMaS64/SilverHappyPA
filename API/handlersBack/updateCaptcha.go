package handlersBack

import (
	"database/sql"
	"net/http"
)

func UpdateCaptcha(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		question := r.FormValue("question")
		answer := r.FormValue("answer")

		updateStatement, updateError := database.Prepare("UPDATE CAPTCHA SET question = ?, answer = ? WHERE ID_CAPTCHA = ?")

		if updateError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pagecaptcha", 303)
			return

		}
		defer updateStatement.Close()

		_, updateExecError := updateStatement.Exec(question, answer, id)

		if updateExecError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pagecaptcha", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=update_success#pagecaptcha", 303)

	}

}