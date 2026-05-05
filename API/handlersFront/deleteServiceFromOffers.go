package handlersFront

import (
	"database/sql"
	"net/http"
)

func DeleteServiceFromOffers(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idProvider := r.FormValue("id_service_provider")
		idService := r.FormValue("id_service")

		database.Exec("DELETE FROM SERVICE_SLOT WHERE ID_SERVICE_PROVIDER = ? AND ID_SERVICE = ? AND is_booked = 0", idProvider, idService)

		deleteStatement, deleteError := database.Prepare("DELETE FROM OFFER WHERE ID_SERVICE_PROVIDER = ? AND ID_SERVICE = ?")

		if deleteError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=delete_service_error", 303)
			return

		}
		defer deleteStatement.Close()

		res, deleteExecError := deleteStatement.Exec(idProvider, idService)

		if deleteExecError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=delete_service_error", 303)
			return

		}

		affected, _ := res.RowsAffected()

		if affected == 0 {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=delete_service_error", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?notif=service_deleted", 303)

	}

}