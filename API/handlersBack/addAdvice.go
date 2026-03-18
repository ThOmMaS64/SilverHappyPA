package handlersBack

import (
	"database/sql"
	"net/http"
)

func AddAdvice(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		title:= r.FormValue("title")
		theme:= r.FormValue("theme")
		description:= r.FormValue("description")

		insertStatement, insertError := database.Prepare("INSERT INTO ADVICE(title, theme, description, date_publication, ID_SERVICE_PROVIDER) VALUES(?, ?, ?, CURDATE(), ?)")

		if insertError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pagetips", 303)
			return 

		}
		defer insertStatement.Close()

		_, insertStatementExecError := insertStatement.Exec(title, theme, description, 1)

		if insertStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pagetips", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=add_success#pagetips", 303)

	}

}