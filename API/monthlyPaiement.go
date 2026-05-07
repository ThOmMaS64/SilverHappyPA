package main

import (
	"bytes"
	"database/sql"
	"encoding/json"
	"fmt"
	"net/http"
	"os"
	"time"

	"github.com/johnfercher/maroto/v2"
	"github.com/johnfercher/maroto/v2/pkg/components/col"
	mImage "github.com/johnfercher/maroto/v2/pkg/components/image"
	"github.com/johnfercher/maroto/v2/pkg/components/line"
	mRow "github.com/johnfercher/maroto/v2/pkg/components/row"
	"github.com/johnfercher/maroto/v2/pkg/components/text"
	marotoConfig "github.com/johnfercher/maroto/v2/pkg/config"
	"github.com/johnfercher/maroto/v2/pkg/consts/align"
	"github.com/johnfercher/maroto/v2/pkg/consts/fontstyle"
	"github.com/johnfercher/maroto/v2/pkg/consts/orientation"
	"github.com/johnfercher/maroto/v2/pkg/consts/pagesize"
	"github.com/johnfercher/maroto/v2/pkg/props"
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

		rows, err := database.Query("SELECT SERVICE_PROVIDER_INVOICE.ID_SERVICE_PROVIDER_INVOICE, SERVICE_PROVIDER_INVOICE.amount, SERVICE_PROVIDER_INVOICE.nb_services_provided, SERVICE_PROVIDER.stripe_account_id, USER_.name, USER_.surname, USER_.city, USER_.street, USER_.nb_street, USER_.postal_code, USER_.email FROM SERVICE_PROVIDER_INVOICE INNER JOIN SERVICE_PROVIDER ON SERVICE_PROVIDER_INVOICE.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER WHERE SERVICE_PROVIDER_INVOICE.month_billed = ? AND SERVICE_PROVIDER_INVOICE.year_billed = ? AND SERVICE_PROVIDER_INVOICE.is_paid = 0 AND SERVICE_PROVIDER.stripe_account_id IS NOT NULL", targetMonth, targetYear)

		if err != nil {

			continue

		}

		for rows.Next() {

			var idInvoice int
			var amount float64
			var nbServices int
			var stripeAccountId string
			var name, surname, city, street, postalCode, email string
			var nbStreet int

			if rows.Scan(&idInvoice, &amount, &nbServices, &stripeAccountId, &name, &surname, &city, &street, &nbStreet, &postalCode, &email) != nil {
				
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

			fmt.Printf("Virement envoyé. Mise à jour de la BDD pour la facture %d\n", idInvoice)

			filename := generateProviderInvoicePDF(idInvoice, targetMonth, targetYear, amount, nbServices, name, surname, city, street, nbStreet, postalCode, email)

			database.Exec("UPDATE SERVICE_PROVIDER_INVOICE SET is_paid = 1, pdf_path = ? WHERE ID_SERVICE_PROVIDER_INVOICE = ?", filename, idInvoice)

		}

		rows.Close()

	}

}

func generateProviderInvoicePDF(idInvoice int, month int, year int, amount float64, nbServices int, name string, surname string, city string, street string, nbStreet int, postalCode string, email string) string {

	date := time.Now().Format("02/01/2006")
	filename := fmt.Sprintf("invoice_provider_%d_%d_%d.pdf", idInvoice, month, year)
	pathName := fmt.Sprintf("../data/invoices_provider/%s", filename)

	cfg := marotoConfig.NewBuilder().WithPageSize(pagesize.A4).WithLeftMargin(10).WithRightMargin(10).WithTopMargin(10).WithBottomMargin(10).WithOrientation(orientation.Vertical).Build()
	invoice := maroto.New(cfg)

	invoice.AddRows(
		mRow.New(20).Add(
			col.New(4).Add(mImage.NewFromFile("../medias/logos/logoComplet.png")),
			col.New(8).Add(text.New("FACTURE - Paiement prestataire", props.Text{Size: 24, Style: fontstyle.Bold, Align: align.Right})),
		),
		mRow.New(15),
	)

	invoice.AddRows(
		mRow.New(6).Add(
			col.New(6).Add(text.New(fmt.Sprintf("Date d'émission : %s", date), props.Text{Style: fontstyle.Bold})),
			col.New(6).Add(text.New(fmt.Sprintf("Identifiant : provider_%d_%d_%d", idInvoice, month, year), props.Text{Style: fontstyle.Bold, Align: align.Right})),
		),
		mRow.New(8),
	)

	invoice.AddRows(
		mRow.New(6).Add(
			col.New(6).Add(text.New("Émetteur :", props.Text{Style: fontstyle.Bold})),
			col.New(6).Add(text.New("Destinataire :", props.Text{Style: fontstyle.Bold})),
		),
		mRow.New(5).Add(
			col.New(6).Add(text.New("Silver Happy")),
			col.New(6).Add(text.New(fmt.Sprintf("%s %s", name, surname))),
		),
		mRow.New(5).Add(
			col.New(6).Add(text.New("244, rue du Faubourg Saint Antoine")),
			col.New(6).Add(text.New(fmt.Sprintf("%d, %s", nbStreet, street))),
		),
		mRow.New(5).Add(
			col.New(6).Add(text.New("75011, Paris")),
			col.New(6).Add(text.New(fmt.Sprintf("%s, %s", postalCode, city))),
		),
		mRow.New(5).Add(
			col.New(6).Add(text.New("silverhappy@gmail.com")),
			col.New(6).Add(text.New(email)),
		),
		mRow.New(5).Add(
			col.New(6).Add(text.New("SIRET : 123 456 789 12345")),
		),
		mRow.New(10),
	)

	invoice.AddRows(
		mRow.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 0, Green: 0, Blue: 0}}))),
		mRow.New(10),
	)

	invoice.AddRows(
		mRow.New(6).Add(
			col.New(6).Add(text.New("Détail", props.Text{Style: fontstyle.Bold})),
			col.New(3).Add(text.New("Montant", props.Text{Style: fontstyle.Bold, Align: align.Right})),
		),
		mRow.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 150, Green: 150, Blue: 150}}))),
		mRow.New(5),
	)

	invoice.AddRows(
		mRow.New(10).Add(
			col.New(6).Add(text.New(fmt.Sprintf("%d prestation(s) réalisée(s) en %02d/%d", nbServices, month, year))),
			col.New(3).Add(text.New(fmt.Sprintf("%.2f €", amount), props.Text{Align: align.Right})),
		),
		mRow.New(10),
	)

	invoice.AddRows(
		mRow.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 0, Green: 0, Blue: 0}}))),
		mRow.New(5),
	)

	invoice.AddRows(
		mRow.New(10).Add(
			col.New(8).Add(text.New("TOTAL :", props.Text{Style: fontstyle.Bold, Align: align.Right})),
			col.New(4).Add(text.New(fmt.Sprintf("%.2f €", amount), props.Text{Size: 12, Style: fontstyle.Bold, Align: align.Right})),
		),
		mRow.New(20),
	)

	invoice.AddRows(
		mRow.New(10).Add(
			col.New(12).Add(text.New("Merci pour votre confiance, l'équipe Silver Happy", props.Text{Align: align.Center, Style: fontstyle.Italic})),
		),
	)

	doc, err := invoice.Generate()

	if err != nil {
		return ""
	}

	if err := os.WriteFile(pathName, doc.GetBytes(), 0644); err != nil {
		return ""
	}

	return filename

}