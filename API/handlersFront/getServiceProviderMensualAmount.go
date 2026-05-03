package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"time"
)

type MensualAmount struct{

	Amount float64 `json:"amount"`
	NbServicesProvided int `json:"nb_services_provided"`

}

func GetServiceProviderMensualAmount(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idServiceProvider := r.FormValue("id_service_provider")

		var mensualAmount MensualAmount

		now := time.Now()
		month := int(now.Month())
		year := now.Year()

		row := database.QueryRow("SELECT amount, nb_services_provided FROM SERVICE_PROVIDER_INVOICE WHERE ID_SERVICE_PROVIDER = ? AND month_billed = ? AND year_billed = ?", idServiceProvider, month, year)
	
		err := row.Scan(&mensualAmount.Amount, &mensualAmount.NbServicesProvided)

		if err == sql.ErrNoRows {

			mensualAmount.Amount = 0.0
			mensualAmount.NbServicesProvided = 0

		} else if err != nil {

			http.Error(w, "Erreur système1", 500)
			return

		}

		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(mensualAmount)

	}
	
}