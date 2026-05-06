package handlersBack

import (
	"database/sql"
	"net/http"
)

func RefuseServiceProviderDocument(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idServiceProvider := r.FormValue("id_service_provider")
		idService := r.FormValue("id_service")

		deleteStatement, deleteError := database.Prepare("DELETE FROM OFFER WHERE ID_SERVICE_PROVIDER = ? AND ID_SERVICE = ?")

		if deleteError != nil {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageusers", 303)
			return
		}
		defer deleteStatement.Close()

		_, deleteExecError := deleteStatement.Exec(idServiceProvider, idService)

		if deleteExecError != nil {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageusers", 303)
			return
		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=delete_success#pageusers", 303)

	}

}