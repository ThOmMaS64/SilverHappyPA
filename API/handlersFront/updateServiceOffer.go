package handlersFront

import (
	"database/sql"
	"net/http"
	"strconv"
)

func UpdateServiceOffer(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idProvider := r.FormValue("id_service_provider")
		idService := r.FormValue("id_service")
		pricingType := r.FormValue("pricing_type")
		cost := r.FormValue("cost")

		var finalCost sql.NullFloat64
		if cost != "" {
			val, _ := strconv.ParseFloat(cost, 64)
			finalCost = sql.NullFloat64{Float64: val, Valid: true}
		}

		updateStatement, updateError := database.Prepare("UPDATE OFFER SET pricing_type = ?, cost = ? WHERE ID_SERVICE_PROVIDER = ? AND ID_SERVICE = ?")

		if updateError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=update_service_error", 303)
			return

		}
		defer updateStatement.Close()

		_, updateExecError := updateStatement.Exec(pricingType, finalCost, idProvider, idService)

		if updateExecError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=update_service_error", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?notif=service_updated", 303)

	}

}