package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ResponseStripeAccountId struct {
	StripeAccountId *string `json:"stripe_account_id"`
}

func GetStripeAccountId(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		idServiceProvider := r.FormValue("id_service_provider")

		response := ResponseStripeAccountId{}

		row := database.QueryRow("SELECT stripe_account_id FROM SERVICE_PROVIDER WHERE ID_SERVICE_PROVIDER = ?", idServiceProvider)

		err := row.Scan(&response.StripeAccountId)

		if err != nil {
			json.NewEncoder(w).Encode(response)
			return
		}

		json.NewEncoder(w).Encode(response)

	}

}