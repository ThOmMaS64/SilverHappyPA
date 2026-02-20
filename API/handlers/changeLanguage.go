package handlers

import (
	"database/sql"
	"net/http"
)


func ChangeLanguage(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		if r.Method != "POST" {
		
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/index.php?error=system1", 303)
			return
		
		}

		errorParse := r.ParseForm()

		if errorParse != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/connexion.php?error=system2", 303)
			return

		}

		language := r.FormValue("language")
		id := r.FormValue("id")

		updateStatement, updateError := database.Prepare("UPDATE USER_ SET language =? WHERE ID_USER = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/index.php?error=system3", 303)
			return 			

		}
		defer updateStatement.Close()

		_, updateExecError := updateStatement.Exec(language, id)

		if updateExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/index.php?error=system4", 303)
			return 					

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/index.php?language_changement_request=1", 303)
		 
	}

}