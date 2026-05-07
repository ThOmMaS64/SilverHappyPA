package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ProviderInvoiceProfile struct {
	ID_SERVICE_PROVIDER_INVOICE int `json:"ID_SERVICE_PROVIDER_INVOICE"`
	Amount string `json:"amount"`
	NbServicesProvided string `json:"nb_services_provided"`
	MonthBilled string `json:"month_billed"`
	YearBilled string `json:"year_billed"`
	IsPaid bool `json:"is_paid"`
	PdfPath string `json:"pdf_path"`
}

type ResponseProviderInvoicesProfile struct {
	Invoices []ProviderInvoiceProfile `json:"invoices"`
	Error    string                   `json:"error"`
}

func ShowProviderInvoicesForProfile(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseProviderInvoicesProfile{
			Invoices: []ProviderInvoiceProfile{},
		}

		idServiceProvider := r.FormValue("id_service_provider")

		rows, err := database.Query("SELECT ID_SERVICE_PROVIDER_INVOICE, amount, nb_services_provided, month_billed, year_billed, is_paid, COALESCE(pdf_path, '') FROM SERVICE_PROVIDER_INVOICE WHERE ID_SERVICE_PROVIDER = ? ORDER BY year_billed DESC, month_billed DESC", idServiceProvider)

		if err != nil {

			response.Error = "Erreur système."
			json.NewEncoder(w).Encode(response)
			return

		}
		defer rows.Close()

		for rows.Next() {

			var invoice ProviderInvoiceProfile

			err := rows.Scan(&invoice.ID_SERVICE_PROVIDER_INVOICE, &invoice.Amount, &invoice.NbServicesProvided, &invoice.MonthBilled, &invoice.YearBilled, &invoice.IsPaid, &invoice.PdfPath)

			if err == nil {

				response.Invoices = append(response.Invoices, invoice)
				
			}

		}

		json.NewEncoder(w).Encode(response)

	}

}