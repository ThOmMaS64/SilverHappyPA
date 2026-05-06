package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

type ConsumerInvoice struct {
	ID_CONSUMER_INVOICE int `json:"ID_CONSUMER_INVOICE"`
	Identifier string `json:"identifier"`
	Type string `json:"type"`
	DateEmission string `json:"date_emission"`
	Amount string `json:"amount"`
	PdfPath string `json:"pdf_path"`
	Username string `json:"username"`
}

type ResponseConsumerInvoices struct {
	Invoices []ConsumerInvoice `json:"invoices"`
	Types []string `json:"types"`
}

func ShowConsumerInvoicesDefaultData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		offsetString := r.FormValue("offset")
		offset := 0

		if offsetString != "" {

			offset, _ = strconv.Atoi(offsetString)

		}

		sort   := r.FormValue("sort")
		filter := r.FormValue("filter")

		response := ResponseConsumerInvoices{
			Invoices: []ConsumerInvoice{},
			Types:    []string{},
		}

		rowsTypes, errTypes := database.Query("SELECT DISTINCT type FROM CONSUMER_INVOICE ORDER BY type ASC")

		if errTypes != nil {
			http.Error(w, "Erreur types.", 500)
			return
		}
		defer rowsTypes.Close()

		for rowsTypes.Next() {
			var t string
			if rowsTypes.Scan(&t) == nil {
				response.Types = append(response.Types, t)
			}
		}

		query := "SELECT CONSUMER_INVOICE.ID_CONSUMER_INVOICE, CONSUMER_INVOICE.identifier, CONSUMER_INVOICE.type, CONSUMER_INVOICE.date_emission, CONSUMER_INVOICE.amount, CONSUMER_INVOICE.pdf_path, USER_.username FROM CONSUMER_INVOICE INNER JOIN CONSUMER ON CONSUMER_INVOICE.ID_CONSUMER = CONSUMER.ID_CONSUMER INNER JOIN USER_ ON CONSUMER.ID_USER = USER_.ID_USER WHERE 1=1"

		var args []any

		if filter != "" {

			query += " AND CONSUMER_INVOICE.type = ?"
			args = append(args, filter)

		}

		if sort == "1" {

			query += " ORDER BY CONSUMER_INVOICE.date_emission ASC"

		} else if sort == "2" {

			query += " ORDER BY CONSUMER_INVOICE.date_emission DESC"

		} else {

			query += " ORDER BY CONSUMER_INVOICE.date_emission DESC"

		}

		query += " LIMIT 10 OFFSET ?"
		args = append(args, offset)

		rows, err := database.Query(query, args...)

		if err != nil {

			http.Error(w, "Erreur système1.", 500)
			return

		}
		defer rows.Close()

		for rows.Next() {

			var invoice ConsumerInvoice

			err := rows.Scan(&invoice.ID_CONSUMER_INVOICE, &invoice.Identifier, &invoice.Type, &invoice.DateEmission, &invoice.Amount, &invoice.PdfPath, &invoice.Username)

			if err == nil {

				response.Invoices = append(response.Invoices, invoice)
				
			}

		}

		json.NewEncoder(w).Encode(response)

	}

}