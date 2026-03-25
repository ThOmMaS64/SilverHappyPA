package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

func ShowInvoicesPersonalizedData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		research := r.FormValue("research")
		sort := r.FormValue("sort")
		
		offsetString := r.FormValue("offset")
		offset := 0

		if offsetString != ""{

			offset, _ = strconv.Atoi(offsetString)

		}

		response := ResponseInvoices{
			Invoices: []Invoice{},
		}

		var args []any
		 
		basicQuery := "SELECT INVOICE.ID_INVOICE, INVOICE.amount, INVOICE.nb_services_provided, INVOICE.month_billed, INVOICE.year_billed, INVOICE.pdf_path, USER_.username FROM INVOICE INNER JOIN SERVICE_PROVIDER ON INVOICE.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER WHERE 1=1"

		if research != ""{

			basicQuery += " AND (USER_.username LIKE CONCAT('%', ?, '%'))"
			args = append(args, research)

		}

		if sort != ""{

			if sort == "1"{

				basicQuery += " ORDER BY INVOICE.year_billed ASC"

			}else if sort == "2"{

				basicQuery += " ORDER BY INVOICE.year_billed DESC"

			}else if sort == "3"{

				basicQuery += " ORDER BY INVOICE.amount ASC"

			}else if sort == "4"{

				basicQuery += " ORDER BY INVOICE.amount DESC"

			}

		}

		basicQuery += " LIMIT 10 OFFSET ?"
		args = append(args, offset)

		rowSelectInvoices, errSelectInvoices := database.Query(basicQuery, args...)
	
		if errSelectInvoices != nil{

			http.Error(w, "Erreur lors de la récupération des factures depuis la base de donnée.", 500)
			return 

		}
		defer rowSelectInvoices.Close()

		for rowSelectInvoices.Next(){

			var invoice Invoice

			err := rowSelectInvoices.Scan(&invoice.ID_INVOICE, &invoice.Amount, &invoice.NbServicesProvided, &invoice.MonthBilled, &invoice.YearBilled, &invoice.PdfPath, &invoice.ServiceProvider)

			if err == nil{

				response.Invoices = append(response.Invoices, invoice)

			}
		}

		json.NewEncoder(w).Encode(response)

	}

}