package handlersBack

import (
	"database/sql"
	"net/http"
)

func AddService(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		serviceType:= r.FormValue("type")
		formation := r.FormValue("formation")
		place := r.FormValue("place")
		cost := r.FormValue("cost")
		isMedicalConfidential := r.FormValue("is_medical_confidential")

		insertStatement, insertError := database.Prepare("INSERT INTO SERVICE(type, formation, place, cost, is_medical_confidential) VALUES(?, ?, ?, ?, ?)")

		if insertError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pageservices", 303)
			return 

		}
		defer insertStatement.Close()

		_, insertStatementExecError := insertStatement.Exec(serviceType, formation, place, cost, isMedicalConfidential)

		if insertStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pageservices", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=add_success#pageservices", 303)

	}

}