package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

type ServiceProviderInvoice struct {
	ID_SERVICE_PROVIDER_INVOICE int `json:"ID_SERVICE_PROVIDER_INVOICE"`
	Amount string `json:"amount"`
	NbServicesProvided string `json:"nb_services_provided"`
	MonthBilled string `json:"month_billed"`
	YearBilled string `json:"year_billed"`
	Username string `json:"username"`
	IsPaid bool `json:"is_paid"`
	PdfPath string `json:"pdf_path"`
}

type ResponseServiceProviderInvoices struct {
	Invoices []ServiceProviderInvoice `json:"invoices"`
}

func ShowServiceProviderInvoicesDefaultData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		offsetString := r.FormValue("offset")
		offset := 0

		if offsetString != "" {

			offset, _ = strconv.Atoi(offsetString)
			
		}

		sort := r.FormValue("sort")
		isPaid := r.FormValue("filter_paid")

		response := ResponseServiceProviderInvoices{
			Invoices: []ServiceProviderInvoice{},
		}

		query := "SELECT SERVICE_PROVIDER_INVOICE.ID_SERVICE_PROVIDER_INVOICE, SERVICE_PROVIDER_INVOICE.amount, SERVICE_PROVIDER_INVOICE.nb_services_provided, SERVICE_PROVIDER_INVOICE.month_billed, SERVICE_PROVIDER_INVOICE.year_billed, USER_.username, SERVICE_PROVIDER_INVOICE.is_paid, COALESCE(SERVICE_PROVIDER_INVOICE.pdf_path, '') FROM SERVICE_PROVIDER_INVOICE INNER JOIN SERVICE_PROVIDER ON SERVICE_PROVIDER_INVOICE.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER"

		if isPaid == "1" {

			query += " AND SERVICE_PROVIDER_INVOICE.is_paid = 1"

		} else if isPaid == "0" {

			query += " AND SERVICE_PROVIDER_INVOICE.is_paid = 0"
			
		}
		
		if sort == "1" {

			query += " ORDER BY SERVICE_PROVIDER_INVOICE.year_billed ASC, SERVICE_PROVIDER_INVOICE.month_billed ASC"

		} else if sort == "2" {

			query += " ORDER BY SERVICE_PROVIDER_INVOICE.year_billed DESC, SERVICE_PROVIDER_INVOICE.month_billed DESC"

		} else {

			query += " ORDER BY SERVICE_PROVIDER_INVOICE.year_billed DESC, SERVICE_PROVIDER_INVOICE.month_billed DESC"

		}

		query += " LIMIT 10 OFFSET ?"

		rows, err := database.Query(query, offset)

		if err != nil {

			http.Error(w, "Erreur système1.", 500)
			return
			
		}
		defer rows.Close()

		for rows.Next() {

			var invoice ServiceProviderInvoice

			err := rows.Scan(&invoice.ID_SERVICE_PROVIDER_INVOICE, &invoice.Amount, &invoice.NbServicesProvided, &invoice.MonthBilled, &invoice.YearBilled, &invoice.Username, &invoice.IsPaid, &invoice.PdfPath)
			
			if err == nil {
				response.Invoices = append(response.Invoices, invoice)
			}

		}

		json.NewEncoder(w).Encode(response)

	}

}