package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ServiceProviderRequest struct {
	ID_DOCUMENT int `json:"id_document"`
	ID_SERVICE_PROVIDER int `json:"id_service_provider"`
	ID_SERVICE int `json:"id_service"`
	Username string `json:"username"`
	ServiceType string `json:"service_type"`
	DocType string `json:"doc_type"`
	DocPath string `json:"doc_path"`
	UploadDate string `json:"upload_date"`
	OfferStatus int `json:"offer_status"`
	DocStatus int `json:"doc_status"`
}

type ResponseServiceProviderRequests struct {
	Requests []ServiceProviderRequest `json:"requests"`
}

func GetServiceProviderRequests(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseServiceProviderRequests{
			Requests: []ServiceProviderRequest{},
		}

		rows, err := database.Query("SELECT SERVICE.ID_SERVICE, SERVICE_PROVIDER_DOCUMENT.ID_DOCUMENT, SERVICE_PROVIDER.ID_SERVICE_PROVIDER, USER_.username, SERVICE.type, SERVICE_PROVIDER_DOCUMENT.type, SERVICE_PROVIDER_DOCUMENT.docPath, SERVICE_PROVIDER_DOCUMENT.uploadDate, OFFER.status, SERVICE_PROVIDER_DOCUMENT.status FROM OFFER INNER JOIN SERVICE_PROVIDER ON OFFER.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER INNER JOIN SERVICE ON OFFER.ID_SERVICE = SERVICE.ID_SERVICE INNER JOIN SERVICE_DOCUMENT ON SERVICE.ID_SERVICE = SERVICE_DOCUMENT.ID_SERVICE INNER JOIN REQUIRED_DOCUMENT ON SERVICE_DOCUMENT.ID_REQUIRED_DOCUMENT = REQUIRED_DOCUMENT.ID_REQUIRED_DOCUMENT INNER JOIN SERVICE_PROVIDER_DOCUMENT ON SERVICE_PROVIDER.ID_SERVICE_PROVIDER = SERVICE_PROVIDER_DOCUMENT.ID_SERVICE_PROVIDER AND SERVICE_PROVIDER_DOCUMENT.type = REQUIRED_DOCUMENT.name WHERE OFFER.status = 0 ORDER BY USER_.username ASC, SERVICE.type ASC")

		if err != nil {

			http.Error(w, "Erreur système 1.", 500)
			return

		}
		defer rows.Close()

		for rows.Next() {

			var req ServiceProviderRequest

			err := rows.Scan(&req.ID_SERVICE, &req.ID_DOCUMENT, &req.ID_SERVICE_PROVIDER, &req.Username, &req.ServiceType, &req.DocType, &req.DocPath, &req.UploadDate, &req.OfferStatus, &req.DocStatus)

			if err == nil {


				response.Requests = append(response.Requests, req)

			}

		}

		json.NewEncoder(w).Encode(response)

	}

}