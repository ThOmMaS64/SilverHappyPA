package handlersBack

import (
	"database/sql"
	"net/http"
)

func BanUser(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		banned := r.FormValue("banned")

		if banned == "0"{
			banned = "1"
		}else{
			banned = "0"
		}

		updateStatement, updateError := database.Prepare("UPDATE USER_ SET banned = ? WHERE ID_USER = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=ban_error#pageusers", 303)
			return 

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(banned, id)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=ban_error#pageusers", 303)
			return 

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=ban_success#pageusers", 303)

	}

}