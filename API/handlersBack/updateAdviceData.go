package handlersBack

import (
	"database/sql"
	"net/http"
)

func UpdateAdviceData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		title:= r.FormValue("title")
		theme:= r.FormValue("theme")
		description:= r.FormValue("description")

		updateStatement, updateError := database.Prepare("UPDATE ADVICE SET title = ?, theme = ?, description = ? WHERE ID_ADVICE = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error1#pagetips", 303)
			return 

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(title, theme, description, id)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error2#pagetips", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=update_success#pagetips", 303)

	}

}