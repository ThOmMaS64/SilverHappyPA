package handlersBack

import (
	"database/sql"
	"net/http"
)

func AddNotification(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		title:= r.FormValue("title")
		description:= r.FormValue("description")
		typenotif:= r.FormValue("type")
		id:= r.FormValue("id")

		insertStatement, insertError := database.Prepare("INSERT INTO NOTIFICATION(title, description, type, ID_CONSUMER) VALUES(?, ?, ?, ?)")

		if insertError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pagenotifs", 303)
			return 

		}
		defer insertStatement.Close()

		_, insertStatementExecError := insertStatement.Exec(title, typenotif, description, id)

		if insertStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pagenotifs", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=add_success#pagenotifs", 303)

	}

}