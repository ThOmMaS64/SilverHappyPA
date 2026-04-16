package handlersFront

import (
	"database/sql"
	"net/http"
)

func RefuseQuote(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		idQuote := r.FormValue("id_quote")

		var idConsumer int

		row := database.QueryRow("SELECT ID_CONSUMER FROM CONSUMER WHERE ID_USER = ?", id)
	
		err := row.Scan(&idConsumer)

		if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/profile.php?error=system4", 303)
			return	

		}

		updateStatement, updateError := database.Prepare("UPDATE QUOTE SET status = 3 WHERE ID_QUOTE = ? AND ID_CONSUMER = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/profile.php?error=system2", 303)
			return	

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(idQuote, idConsumer)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/profile.php?error=system3", 303)
			return	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/profile.php", 303)	

	}
	
}