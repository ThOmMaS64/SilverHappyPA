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

			http.Error(w, "Erreur lors de la mise à jour des données dans la base de données 1", 500)
			return	

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(banned, id)

		if updateStatementExecError != nil{

			http.Error(w, "Erreur lors de la mise à jour des données dans la base de données 2", 500)
			return	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php#pageusers", 303)

	}

}