package handlersFront

import (
	"database/sql"
	"net/http"
)

func PersonalizeKeyWordDescription(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		if r.FormValue("cancel") != "" {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/profile.php?notif=cancel_success", 303)
			return	

		}

		id := r.FormValue("id")

		description := r.FormValue("description")
		keyWord1 := r.FormValue("keyWord1")
		keyWord2 := r.FormValue("keyWord2")
		keyWord3 := r.FormValue("keyWord3")

		updateStatement, updateError := database.Prepare("UPDATE USER_ SET description = ?, keyWord1 = ?, keyWord2 = ?, keyWord3 = ? WHERE ID_USER = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/profile.php?error=system1", 303)
			return	

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(description, keyWord1, keyWord2, keyWord3, id)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/profile.php?error=system2", 303)
			return	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/profile.php?notif=description_keyword_update_success", 303)
		 
	}

}