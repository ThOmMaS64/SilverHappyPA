package handlersBack

import (
	"database/sql"
	"net/http"
)

func ValidateServiceProviderDocument(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idDocument := r.FormValue("id_document")
		idServiceProvider := r.FormValue("id_service_provider")
		idService := r.FormValue("id_service")

		_, _ = database.Exec("UPDATE SERVICE_PROVIDER_DOCUMENT SET status = 1 WHERE ID_DOCUMENT = ?", idDocument)

		var pendingCount int

		row := database.QueryRow("SELECT COUNT(*) FROM REQUIRED_DOCUMENT JOIN SERVICE_DOCUMENT ON REQUIRED_DOCUMENT.ID_REQUIRED_DOCUMENT = SERVICE_DOCUMENT.ID_REQUIRED_DOCUMENT JOIN SERVICE ON SERVICE_DOCUMENT.ID_SERVICE = SERVICE.ID_SERVICE LEFT JOIN SERVICE_PROVIDER_DOCUMENT ON SERVICE_PROVIDER_DOCUMENT.type = REQUIRED_DOCUMENT.name AND SERVICE_PROVIDER_DOCUMENT.ID_SERVICE_PROVIDER = ? AND SERVICE_PROVIDER_DOCUMENT.status = 1 WHERE SERVICE.ID_SERVICE = ? AND SERVICE_PROVIDER_DOCUMENT.ID_DOCUMENT IS NULL", idServiceProvider, idService)

		err := row.Scan(&pendingCount)

		if err != nil {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pageusers", 303)
			return
		}

		if pendingCount == 0 {

			updateStatement, updateError := database.Prepare("UPDATE OFFER SET status = 1 WHERE ID_SERVICE_PROVIDER = ? AND ID_SERVICE = ?")

			if updateError != nil {
				http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pageusers", 303)
				return
			}
			defer updateStatement.Close()

			_, updateExecError := updateStatement.Exec(idServiceProvider, idService)

			if updateExecError != nil {
				http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pageusers", 303)
				return
			}

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=update_success#pageusers", 303)

	}

}