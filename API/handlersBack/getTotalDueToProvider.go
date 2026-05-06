package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"time"
)

type ResponseTotalDue struct {
	Total string `json:"total"`
	Month int    `json:"month"`
	Year  int    `json:"year"`
	Error string `json:"error"`
}

func GetTotalDueToProviders(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseTotalDue{
			Month: int(time.Now().Month()),
			Year:  time.Now().Year(),
		}

		row := database.QueryRow("SELECT COALESCE(SUM(amount), 0) FROM SERVICE_PROVIDER_INVOICE WHERE month_billed = ? AND year_billed = ?", int(time.Now().Month()), time.Now().Year())

		err := row.Scan(&response.Total)

		if err != nil {

			response.Error = "Erreur système 1."

		}

		json.NewEncoder(w).Encode(response)

	}

}