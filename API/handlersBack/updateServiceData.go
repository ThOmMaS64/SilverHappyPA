package handlersBack

import (
	"database/sql"
	"net/http"
)

func UpdateServiceData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		serviceType:= r.FormValue("type")
		description:= r.FormValue("description")
		formation := r.FormValue("formation")
		place := r.FormValue("place")
		cost := r.FormValue("cost")
		isMedicalConfidential := r.FormValue("is_medical_confidential")

		updateStatement, updateError := database.Prepare("UPDATE SERVICE SET type = ?, description = ?, formation = ?, place = ?, cost = ?, is_medical_confidential = ? WHERE ID_SERVICE = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pageservices", 303)
			return 

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(serviceType, description, formation, place, cost, isMedicalConfidential, id)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pageservices", 303)
			return 	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=update_success#pageservices", 303)

	}

}