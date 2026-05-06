package handlersFront

import (
	"database/sql"
	"net/http"
)

func SaveStripeAccountId(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idServiceProvider := r.FormValue("id_service_provider")
		stripeAccountId := r.FormValue("stripe_account_id")

		updateStatement, updateError := database.Prepare("UPDATE SERVICE_PROVIDER SET stripe_account_id = ? WHERE ID_SERVICE_PROVIDER = ?")

		if updateError != nil {
			
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system1", 303)
			return

		}
		defer updateStatement.Close()

		_, updateExecError := updateStatement.Exec(stripeAccountId, idServiceProvider)

		if updateExecError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system1", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php", 303)

	}

}