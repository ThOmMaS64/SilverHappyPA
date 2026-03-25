package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

type Invoice struct{
	ID_INVOICE int `json:"ID_INVOICE"`
	Amount string `json:"amount"`
	NbServicesProvided string `json:"nb_services_provided"`
	MonthBilled string `json:"month_billed"`
	YearBilled string `json:"year_billed"`
	PdfPath string `json:"pdf_path"`	
	ServiceProvider string`json:"service_provider"`	
}

type ResponseInvoices struct {

	Invoices []Invoice `json:"invoices"`

}

func ShowInvoicesDefaultData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		offsetString := r.FormValue("offset")
		offset := 0

		if offsetString != ""{

			offset, _ = strconv.Atoi(offsetString)

		}

		response := ResponseInvoices{
			Invoices: []Invoice{},
		}

		rowsInvoices, err := database.Query("SELECT INVOICE.ID_INVOICE, INVOICE.amount, INVOICE.nb_services_provided, INVOICE.month_billed, INVOICE.year_billed, INVOICE.pdf_path, USER_.username FROM INVOICE INNER JOIN SERVICE_PROVIDER ON INVOICE.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER LIMIT 10 OFFSET ?", offset)
		if err != nil {
			http.Error(w, err.Error(), 500)
			return 
		}	
		defer rowsInvoices.Close()

		var invoices []Invoice
		
		for rowsInvoices.Next(){

			var invoice Invoice

			err := rowsInvoices.Scan(&invoice.ID_INVOICE, &invoice.Amount, &invoice.NbServicesProvided, &invoice.MonthBilled, &invoice.YearBilled, &invoice.PdfPath, &invoice.ServiceProvider)	
			if err != nil {
				continue
			}

			invoices = append(invoices, invoice)
		}
		
		w.Header().Set("Content-Type", "application/json")
		response.Invoices = invoices
		json.NewEncoder(w).Encode(response)

	}

}