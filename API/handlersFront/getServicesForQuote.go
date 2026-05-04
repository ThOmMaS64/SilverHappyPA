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

		rows, err := database.Query("SELECT SERVICE.ID_SERVICE, SERVICE.type FROM SERVICE JOIN OFFER ON SERVICE.ID_SERVICE = OFFER.ID_SERVICE WHERE OFFER.ID_SERVICE_PROVIDER = ? AND OFFER.pricing_type = 'quote' AND SERVICE.ID_SERVICE NOT IN (SELECT ID_SERVICE FROM QUOTE WHERE ID_CONSUMER = ? AND ID_SERVICE_PROVIDER = ? AND status IN (0, 1, 2))", idServiceProvider, idConsumer, idServiceProvider)
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