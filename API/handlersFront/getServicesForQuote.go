package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ServiceForQuoteForm struct {
	IdService int `json:"id_service"`
	Type string `json:"type"`
}

func GetServicesForQuote(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idDiscussion := r.FormValue("id_discussion")

		var idConsumer int
		var idServiceProvider int

		row := database.QueryRow("SELECT CONSUMER.ID_CONSUMER, SERVICE_PROVIDER.ID_SERVICE_PROVIDER FROM DISCUSSION INNER JOIN CONSUMER ON CONSUMER.ID_USER IN (DISCUSSION.user1_id, DISCUSSION.user2_id) INNER JOIN SERVICE_PROVIDER ON SERVICE_PROVIDER.ID_USER IN (DISCUSSION.user1_id, DISCUSSION.user2_id) WHERE DISCUSSION.ID_DISCUSSION = ?", idDiscussion)

		err := row.Scan(&idConsumer, &idServiceProvider)

		if err != nil {
			w.Header().Set("Content-Type", "application/json")
			json.NewEncoder(w).Encode([]ServiceForQuoteForm{})
			return
		}

		rows, err := database.Query("SELECT SERVICE.ID_SERVICE, COALESCE(SERVICE.type, '') FROM SERVICE INNER JOIN DISCUSSION ON DISCUSSION.ID_SERVICE = SERVICE.ID_SERVICE WHERE DISCUSSION.ID_DISCUSSION = ? AND SERVICE.pricing_type = 'quote' AND NOT EXISTS (SELECT 1 FROM QUOTE WHERE QUOTE.ID_SERVICE = SERVICE.ID_SERVICE AND QUOTE.ID_SERVICE_PROVIDER = ? AND QUOTE.ID_CONSUMER = ? AND QUOTE.status IN (0, 1))", idDiscussion, idServiceProvider, idConsumer)

		if err != nil {
			w.Header().Set("Content-Type", "application/json")
			json.NewEncoder(w).Encode([]ServiceForQuoteForm{})
			return
		}

		services := []ServiceForQuoteForm{}

		for rows.Next() {

			var service ServiceForQuoteForm

			err := rows.Scan(&service.IdService, &service.Type)
			
			if err != nil {
				continue
			}
			services = append(services, service)
		}

		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(services)

	}
	
}