package handlersFront

import (
	"database/sql"
	"net/http"
)

func DeleteServiceSlot(database *sql.DB) http.HandlerFunc {
	return func(w http.ResponseWriter, r *http.Request) {

		idProvider := r.FormValue("id_service_provider")
		idService := r.FormValue("id_service")
		
		startStr := r.FormValue("date") + " " + r.FormValue("start_time") + ":00"
		endStr := r.FormValue("date") + " " + r.FormValue("end_time") + ":00"

		res, err := database.Exec("DELETE FROM SERVICE_SLOT WHERE ID_SERVICE_PROVIDER = ? AND ID_SERVICE = ? AND start_time = ? AND end_time = ? AND is_booked = 0", idProvider, idService, startStr, endStr)

		if err != nil {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=delete_error", 303)
			return
		}

		affected, _ := res.RowsAffected()

		if affected == 0 {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=delete_error", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?success=slot_deleted", 303)
	}
}