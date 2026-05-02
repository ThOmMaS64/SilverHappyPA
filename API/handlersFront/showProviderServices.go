package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ServiceInfo struct {
	ID int `json:"id"`
	Type string `json:"type"`
}

type ResponseProviderServices struct {
	Services []ServiceInfo `json:"services"`
	Error string `json:"error"`
}

func ShowProviderServices(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {
		
		w.Header().Set("Content-Type", "application/json")

		response := ResponseProviderServices{

			Services: []ServiceInfo{},

		}

		idProvider := r.FormValue("id_provider")

		rows, err := database.Query("SELECT SERVICE.ID_SERVICE, SERVICE.type FROM SERVICE JOIN OFFER ON SERVICE.ID_SERVICE = OFFER.ID_SERVICE WHERE OFFER.ID_SERVICE_PROVIDER = ? AND SERVICE.pricing_type = 'fixed' ORDER BY SERVICE.type ASC", idProvider)
		
		if err != nil {

			response.Error = "Erreur système."
			json.NewEncoder(w).Encode(response)
			return

		}
		defer rows.Close()

		for rows.Next() {

			var service ServiceInfo

			err := rows.Scan(&service.ID, &service.Type)

			if err == nil {

				response.Services = append(response.Services, service)
				
			}
		}

		json.NewEncoder(w).Encode(response)
	}
}