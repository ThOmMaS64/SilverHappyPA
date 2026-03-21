package handlersBack

import (
	"database/sql"
	"net/http"
)

func UpdateEventData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		eventType := r.FormValue("type")
		name := r.FormValue("name")
		date_start := r.FormValue("date_start")
		date_end := r.FormValue("date_end")
		description := r.FormValue("description")
		city := r.FormValue("city")
		street := r.FormValue("street")
		nb_street := r.FormValue("nb_street")
		postal_code := r.FormValue("postal_code")
		id_work_address := r.FormValue("ID_WORK_ADDRESS")

		updateStatement, updateError := database.Prepare("UPDATE EVENT SET type = ?, name = ?, date_start = ?, date_end = ?, description = ? WHERE ID_EVENT = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pageevents", 303)
			return 

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(eventType, name, date_start, date_end, description, id)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pageevents", 303)
			return

		}

		updateStatement2, updateError2 := database.Prepare("UPDATE WORK_ADDRESS SET city = ?, street = ?, nb_street = ?, postal_code = ? WHERE ID_WORK_ADDRESS = ?")

		if updateError2 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pageevents", 303)
			return 

		}
		defer updateStatement2.Close()

		_, updateStatementExecError2 := updateStatement2.Exec(city, street, nb_street, postal_code, id_work_address)

		if updateStatementExecError2 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pageevents", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=update_success#pageevents", 303)

	}

}