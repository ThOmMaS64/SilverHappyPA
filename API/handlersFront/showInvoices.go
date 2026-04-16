package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type Invoice struct{

	IdConsumerInvoice int `json:"id_consumer_invoice"`
	Identifier string `json:"identifier"`
	InvoiceType string `json:"invoice_type"`
	DateEmission string `json:"date_emission"`
	Amount string `json:"amount"`
	PdfPath string `json:"pdf_path"`

}

type ResponseInvoice struct {

	Invoices []Invoice `json:"invoices"`
	Error string `json:"error"`

}

func ShowInvoices(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseInvoice{
			Invoices: []Invoice{},
		}

		id := r.FormValue("id")

		rowSelectInvoices, errSelectInvoices := database.Query("SELECT CONSUMER_INVOICE.ID_CONSUMER_INVOICE, CONSUMER_INVOICE.identifier, CONSUMER_INVOICE.type, CONSUMER_INVOICE.date_emission, CONSUMER_INVOICE.amount, CONSUMER_INVOICE.pdf_path FROM CONSUMER_INVOICE INNER JOIN CONSUMER ON CONSUMER_INVOICE.ID_CONSUMER = CONSUMER.ID_CONSUMER WHERE CONSUMER.ID_USER = ? ORDER BY CONSUMER_INVOICE.date_emission DESC", id)
	
		if errSelectInvoices != nil{

			w.WriteHeader(500)
			response.Error = "Erreur lors de la récupération des Factures depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}
		defer rowSelectInvoices.Close()

		for rowSelectInvoices.Next(){

			var invoice Invoice

			err := rowSelectInvoices.Scan(&invoice.IdConsumerInvoice, &invoice.Identifier, &invoice.InvoiceType, &invoice.DateEmission, &invoice.Amount, &invoice.PdfPath)

			if err == nil{

				response.Invoices = append(response.Invoices, invoice)

			}
		}

		json.NewEncoder(w).Encode(response)
		 
	}

}