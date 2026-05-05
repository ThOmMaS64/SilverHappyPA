package handlersBack

import (
	"database/sql"
	"net/http"
)

func AddCaptcha(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		question := r.FormValue("question")
		answer := r.FormValue("answer")

		insertStatement, insertError := database.Prepare("INSERT INTO CAPTCHA (question, answer) VALUES (?, ?)")

		if insertError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pagecaptcha", 303)
			return

		}
		defer insertStatement.Close()

		_, insertExecError := insertStatement.Exec(question, answer)

		if insertExecError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pagecaptcha", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=add_success#pagecaptcha", 303)

	}

}