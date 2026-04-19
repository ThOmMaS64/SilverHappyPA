package handlersFront

import (
	"database/sql"
	"fmt"
	"net/http"
	"os"
	"strings"
	"time"

	"github.com/johnfercher/maroto/v2"
	"github.com/johnfercher/maroto/v2/pkg/components/col"
	mImage "github.com/johnfercher/maroto/v2/pkg/components/image"
	"github.com/johnfercher/maroto/v2/pkg/components/line"
	mRow "github.com/johnfercher/maroto/v2/pkg/components/row"
	"github.com/johnfercher/maroto/v2/pkg/components/text"
	"github.com/johnfercher/maroto/v2/pkg/config"
	"github.com/johnfercher/maroto/v2/pkg/consts/align"
	"github.com/johnfercher/maroto/v2/pkg/consts/fontstyle"
	"github.com/johnfercher/maroto/v2/pkg/consts/orientation"
	"github.com/johnfercher/maroto/v2/pkg/consts/pagesize"
	"github.com/johnfercher/maroto/v2/pkg/props"
)
func GenerateQuoteServiceInvoice(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idConsumer := r.FormValue("id_consumer")

		implodedQuotesId := r.FormValue("id_quotes")
		quotesId := strings.Split(implodedQuotesId, "-")

		var idUser1 int
		var name1 string
		var surname1 string
		var city1 string
		var street1 string
		var nbStreet1 int
		var postalCode1 string
		var email1 string

		finalCost := 0.0
		var commission float64

		rowUser := database.QueryRow("SELECT USER_.ID_USER, USER_.name, USER_.surname, USER_.city, USER_.street, USER_.nb_street, USER_.postal_code, USER_.email FROM CONSUMER INNER JOIN USER_ ON CONSUMER.ID_USER = USER_.ID_USER WHERE CONSUMER.ID_CONSUMER = ?", idConsumer)
		
		errUser := rowUser.Scan(&idUser1, &name1, &surname1, &city1, &street1, &nbStreet1, &postalCode1, &email1)

		if errUser != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?&error=invoice_error", 303)	
			return 

		}

		date := time.Now().Format(("02/01/2006"))

		dateIdentifier := time.Now().Format(("2006-01-02"))
		identifier := "service_" + idConsumer + "_" + implodedQuotesId + "_" + dateIdentifier

		config := config.NewBuilder().WithPageSize(pagesize.A4).WithLeftMargin(10).WithRightMargin(10).WithTopMargin(10).WithBottomMargin(10).WithOrientation(orientation.Vertical).Build()
		invoice := maroto.New(config)

		invoice.AddRows(
			mRow.New(20).Add(
				col.New(4).Add(mImage.NewFromFile("../medias/logos/logoComplet.png")),
				col.New(8).Add(text.New("FACTURE - Devis prestation.s de service.s", props.Text{Size: 24, Style: fontstyle.Bold, Align: align.Right})),
			),
			mRow.New(15),
		)

		invoice.AddRows(
			mRow.New(6).Add(
				col.New(6).Add(text.New(fmt.Sprintf("Date d'émission : %s", date),
					props.Text{Style: fontstyle.Bold})),
				col.New(6).Add(text.New(fmt.Sprintf("Identifiant : %s", identifier),
					props.Text{Style: fontstyle.Bold, Align: align.Right})),
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
				col.New(6).Add(text.New(fmt.Sprintf("%s %s", name1, surname1))),
			),
			mRow.New(5).Add(
				col.New(6).Add(text.New("244, rue du Faubourg Saint Antoine")),
				col.New(6).Add(text.New(fmt.Sprintf("%d, %s", nbStreet1, street1))),
			),
			mRow.New(5).Add(
				col.New(6).Add(text.New("75011, Paris")),
				col.New(6).Add(text.New(fmt.Sprintf("%s, %s", postalCode1, city1))),
			),
			mRow.New(5).Add(
				col.New(6).Add(text.New("silverhappy@gmail.com")),
				col.New(6).Add(text.New(email1)),
			),
			mRow.New(5).Add(
				col.New(4).Add(text.New("SIRET : 123 456 789 12345")),
			),
			mRow.New(10),
		)

		invoice.AddRows(
			mRow.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 0, Green: 0, Blue: 0}}))), 
			mRow.New(10),
		)

		invoice.AddRows(
			mRow.New(6).Add(
				col.New(4).Add(text.New("Intitulé de la prestation de service", props.Text{Style: fontstyle.Bold})),
				col.New(4).Add(text.New("Prestataire", props.Text{Style: fontstyle.Bold, Align: align.Right})),
				col.New(2).Add(text.New("Prix HT", props.Text{Style: fontstyle.Bold, Align: align.Right})),
				col.New(2).Add(text.New("Prix TTC", props.Text{Style: fontstyle.Bold, Align: align.Right})),
			),
			mRow.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 150, Green: 150, Blue: 150}}))),
			mRow.New(5),
		)

		currentMonth := time.Now().Month()
		currentYear := time.Now().Year()

		for _, quoteId := range quotesId{

			var serviceType string
			var providerName string
			var providerSurname string
			var idServiceProvider int

			var cost float64

			row := database.QueryRow("SELECT COALESCE(QUOTE.prestation, SERVICE.type), QUOTE.amount, USER_.name, USER_.surname, SERVICE_PROVIDER.ID_SERVICE_PROVIDER, SERVICE_PROVIDER.commission FROM QUOTE INNER JOIN SERVICE ON QUOTE.ID_SERVICE = SERVICE.ID_SERVICE INNER JOIN SERVICE_PROVIDER ON QUOTE.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER WHERE QUOTE.ID_QUOTE = ?", quoteId)
				
			err := row.Scan(&serviceType, &cost, &providerName, &providerSurname, &idServiceProvider, &commission)

			if err != nil {
				http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?&error=system10", 303)
				return
			}

			costHT := cost / 1.2
			finalCost += cost

			invoice.AddRows(
				mRow.New(10).Add(
					col.New(4).Add(text.New(fmt.Sprintf("%s", serviceType))),
					col.New(4).Add(text.New(fmt.Sprintf("%s %s", providerName, providerSurname))),
					col.New(2).Add(text.New(fmt.Sprintf("%.2f €", costHT), props.Text{Align: align.Right})),
					col.New(2).Add(text.New(fmt.Sprintf("%.2f €", cost), props.Text{Align: align.Right})),
				),
			)

			providerNewAmount := cost - cost * commission

			var idServiceProviderInvoice int
			var existingAmount float64
			var existingNbServices int

			rowServiceProvider := database.QueryRow("SELECT ID_SERVICE_PROVIDER_INVOICE, amount, nb_services_provided FROM SERVICE_PROVIDER_INVOICE WHERE ID_SERVICE_PROVIDER = ? AND month_billed = ? AND year_billed = ?", idServiceProvider, currentMonth, currentYear)
			
			errServiceProvider := rowServiceProvider.Scan(&idServiceProviderInvoice, &existingAmount, &existingNbServices)

			if errServiceProvider == sql.ErrNoRows{

				insertStatement, insertError := database.Prepare("INSERT INTO SERVICE_PROVIDER_INVOICE(ID_SERVICE_PROVIDER, amount, nb_services_provided, month_billed, year_billed) VALUES(?, ?, ?, ?, ?)")

				if insertError != nil {

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?error=system5", 303)	
					return 

				}
				defer insertStatement.Close()

				_, insertExecError := insertStatement.Exec(idServiceProvider, providerNewAmount, 1, currentMonth, currentYear)

				if insertExecError != nil {

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?error=system6", 303)
					return 

				}

			}else if errServiceProvider != nil {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?&error=system7", 303)	
				return 

			}else if errServiceProvider == nil{

				updateStatement, updateError := database.Prepare("UPDATE SERVICE_PROVIDER_INVOICE SET amount = amount + ?, nb_services_provided = nb_services_provided + 1 WHERE ID_SERVICE_PROVIDER = ? AND month_billed = ? AND year_billed = ?")

				if updateError != nil{

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?error=system8", 303)
					return	

				}
				defer updateStatement.Close()

				_, updateStatementExecError := updateStatement.Exec(providerNewAmount, idServiceProvider, currentMonth, currentYear)

				if updateStatementExecError != nil{

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?error=system9", 303)
					return	

				}

			}

		}

		invoice.AddRows(
			mRow.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 0, Green: 0, Blue: 0}}))),
			mRow.New(5),
		)

		invoice.AddRows(
			mRow.New(10).Add(
				col.New(8).Add(text.New("TOTAL (TVA à 20%) :", props.Text{Style: fontstyle.Bold, Align: align.Right})),
				col.New(4).Add(text.New(fmt.Sprintf("%.2f €", finalCost), props.Text{Size: 12, Style: fontstyle.Bold, Align: align.Right})),
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
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?&error=system1", 303)
			return
		}

		filename := fmt.Sprintf("invoice_service_%s_%s.pdf", implodedQuotesId, idConsumer)
		pathName := fmt.Sprintf("../data/invoices_quotes/%s", filename)

		err = os.WriteFile(pathName, doc.GetBytes(), 0644)

		if err != nil {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?&error=system2", 303)
			return
		}

		insertStatement, insertError := database.Prepare("INSERT INTO CONSUMER_INVOICE(ID_CONSUMER, identifier, type, date_emission, amount, pdf_path) VALUES(?, ?, ?, ?, ?, ?)")

		if insertError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?error=system3", 303)	
			return 

		}
		defer insertStatement.Close()

		_, insertExecError := insertStatement.Exec(idConsumer, identifier, "serviceByQuote", dateIdentifier, finalCost, filename)

		if insertExecError != nil {

			fmt.Println("Erreur d'insertion CONSUMER_INVOICE:", insertExecError)
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?error=system4", 303)
			return 

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?&notif=paiement_success", 303)	

	}
	
}