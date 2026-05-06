package main

import (
	"bytes"
	"database/sql"
	"encoding/json"
	"fmt"
	"net/http"
	"time"
)

const stripeSecretKey = "sk_test_51Szo1M2WiLfhQi4sPPN6NYJI4gecl8Au5rA0oxHK7grJ6H4u41IReSoXwXn0NqrtzqWW8yXOItSD7MvuuW0q0Sgt009dm7h1pa"

func monthlyPaiement(database *sql.DB) {

	for {

		nextRun := time.Date(time.Now().Year(), time.Now().Month()+1, 1, 0, 0, 0, 0, time.Now().Location())
		time.Sleep(time.Until(nextRun))

		targetMonth := int(time.Now().Month()) - 1
		targetYear := time.Now().Year()

		if targetMonth == 0 {
			targetMonth = 12
			targetYear -= 1
		}

		rows, err := database.Query("SELECT SERVICE_PROVIDER_INVOICE.ID_SERVICE_PROVIDER_INVOICE, SERVICE_PROVIDER_INVOICE.amount, SERVICE_PROVIDER.stripe_account_id FROM SERVICE_PROVIDER_INVOICE INNER JOIN SERVICE_PROVIDER ON SERVICE_PROVIDER_INVOICE.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER WHERE SERVICE_PROVIDER_INVOICE.month_billed = ? AND SERVICE_PROVIDER_INVOICE.year_billed = ? AND SERVICE_PROVIDER_INVOICE.is_paid = 0 AND SERVICE_PROVIDER.stripe_account_id IS NOT NULL", targetMonth, targetYear)

		if err != nil {
			continue
		}

		for rows.Next() {

			var idInvoice int
			var amount float64
			var stripeAccountId string

			if rows.Scan(&idInvoice, &amount, &stripeAccountId) != nil {
				continue
			}

			payload := fmt.Sprintf("amount=%d&currency=eur&destination=%s", int(amount*100), stripeAccountId)

			req, err := http.NewRequest("POST", "https://api.stripe.com/v1/transfers", bytes.NewBufferString(payload))

			if err != nil {
				continue
			}

			req.Header.Set("Content-Type", "application/x-www-form-urlencoded")
			req.Header.Set("Authorization", "Bearer "+stripeSecretKey)

			resp, err := (&http.Client{}).Do(req)

			if err != nil {
				continue
			}

			var result map[string]any
			json.NewDecoder(resp.Body).Decode(&result)
			resp.Body.Close()

			if _, hasError := result["error"]; hasError {
				continue
			}

			database.Exec("UPDATE SERVICE_PROVIDER_INVOICE SET is_paid = 1 WHERE ID_SERVICE_PROVIDER_INVOICE = ?", idInvoice)

		}

		rows.Close()

	}

}