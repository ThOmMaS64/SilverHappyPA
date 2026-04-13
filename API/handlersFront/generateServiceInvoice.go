package handlersFront

import (
	"database/sql"
	"fmt"
	"net/http"
	"os"
	"time"

	"github.com/johnfercher/maroto/v2"
	"github.com/johnfercher/maroto/v2/pkg/components/col"
	mImage "github.com/johnfercher/maroto/v2/pkg/components/image"
	"github.com/johnfercher/maroto/v2/pkg/components/line"
	"github.com/johnfercher/maroto/v2/pkg/components/row"
	"github.com/johnfercher/maroto/v2/pkg/components/text"
	"github.com/johnfercher/maroto/v2/pkg/config"
	"github.com/johnfercher/maroto/v2/pkg/consts/align"
	"github.com/johnfercher/maroto/v2/pkg/consts/fontstyle"
	"github.com/johnfercher/maroto/v2/pkg/consts/orientation"
	"github.com/johnfercher/maroto/v2/pkg/consts/pagesize"
	"github.com/johnfercher/maroto/v2/pkg/props"
)
func GenerateServiceInvoice(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idConsumer := r.FormValue("id_consumer")
		idService := r.FormValue("id_service")

		var idUser1 int
		var name1 string
		var surname1 string
		var city1 string
		var street1 string
		var nbStreet1 int
		var postalCode1 string
		var email1 string

		var name2 string
		var surname2 string
		var idServiceProvider int
		var profession string
		var commission float64
		var city2 string
		var street2 string
		var nbStreet2 int
		var postalCode2 string
		var email2 string

		var cost float64
		var serviceType string

		rowUser := database.QueryRow("SELECT USER_.ID_USER, USER_.name, USER_.surname, USER_.city, USER_.street, USER_.nb_street, USER_.postal_code, USER_.email FROM CONSUMER INNER JOIN USER_ ON CONSUMER.ID_USER = USER_.ID_USER WHERE CONSUMER.ID_CONSUMER = ?", idConsumer)
		
		errUser := rowUser.Scan(&idUser1, &name1, &surname1, &city1, &street1, &nbStreet1, &postalCode1, &email1)

		if errUser != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?&error=invoice_error", 303)	
			return 

		}

		rowService := database.QueryRow("SELECT SERVICE.type, SERVICE.cost, USER_.name, USER_.surname, SERVICE_PROVIDER.ID_SERVICE_PROVIDER, SERVICE_PROVIDER.profession, SERVICE_PROVIDER.commission, USER_.city, USER_.street, USER_.nb_street, USER_.postal_code, USER_.email FROM SERVICE INNER JOIN OFFER ON SERVICE.ID_SERVICE = OFFER.ID_SERVICE INNER JOIN SERVICE_PROVIDER ON OFFER.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER WHERE SERVICE.ID_SERVICE = ?", idService)

        errService := rowService.Scan(&serviceType, &cost, &name2, &surname2, &idServiceProvider, &profession, &commission, &city2, &street2, &nbStreet2, &postalCode2, &email2)

        if errService != nil {
            http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?error=invoice_error", 303)
            return
        }

		date := time.Now().Format(("02/01/2006"))

		costHT := cost / 1.2

		dateIdentifier := time.Now().Format(("2006-01-02"))
		identifier := "service_" + idConsumer + "_" + idService + "_" + dateIdentifier

		config := config.NewBuilder().WithPageSize(pagesize.A4).WithLeftMargin(10).WithRightMargin(10).WithTopMargin(10).WithBottomMargin(10).WithOrientation(orientation.Vertical).Build()
		invoice := maroto.New(config)

		invoice.AddRows(
			row.New(20).Add(
				col.New(4).Add(mImage.NewFromFile("../medias/logos/logoComplet.png")),
				col.New(8).Add(text.New("FACTURE - Inscription prestation de service", props.Text{Size: 24, Style: fontstyle.Bold, Align: align.Right})),
			),
			row.New(15),
		)

		invoice.AddRows(
			row.New(6).Add(
				col.New(6).Add(text.New(fmt.Sprintf("Date d'émission : %s", date),
					props.Text{Style: fontstyle.Bold})),
				col.New(6).Add(text.New(fmt.Sprintf("Identifiant : %s", identifier),
					props.Text{Style: fontstyle.Bold, Align: align.Right})),
			),
			row.New(8),
		)

		invoice.AddRows(
			row.New(6).Add(
				col.New(4).Add(text.New("Émetteur :", props.Text{Style: fontstyle.Bold})),
				col.New(4).Add(text.New("Prestataire :", props.Text{Style: fontstyle.Bold})),
				col.New(4).Add(text.New("Destinataire :", props.Text{Style: fontstyle.Bold})),
			),
			row.New(5).Add(
				col.New(4).Add(text.New("Silver Happy")),
				col.New(4).Add(text.New(fmt.Sprintf("%s %s", name2, surname2))),
				col.New(4).Add(text.New(fmt.Sprintf("%s %s", name1, surname1))),
			),
			row.New(5).Add(
				col.New(4).Add(text.New("244, rue du Faubourg Saint Antoine")),
				col.New(4).Add(text.New(fmt.Sprintf("%d, %s", nbStreet2, street2))),
				col.New(4).Add(text.New(fmt.Sprintf("%d, %s", nbStreet1, street1))),
			),
			row.New(5).Add(
				col.New(4).Add(text.New("75011, Paris")),
				col.New(4).Add(text.New(fmt.Sprintf("%s, %s", postalCode2, city2))),
				col.New(4).Add(text.New(fmt.Sprintf("%s, %s", postalCode1, city1))),
			),
			row.New(5).Add(
				col.New(4).Add(text.New("silverhappy@gmail.com")),
				col.New(4).Add(text.New(email2)),
				col.New(4).Add(text.New(email1)),
			),
			row.New(5).Add(
				col.New(4).Add(text.New("SIRET : 123 456 789 12345")),
				col.New(4).Add(text.New(fmt.Sprintf("Profession : %s", profession))),
			),
			row.New(10),
		)

		invoice.AddRows(
			row.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 0, Green: 0, Blue: 0}}))), 
			row.New(10),
		)

		invoice.AddRows(
			row.New(6).Add(
				col.New(6).Add(text.New("Intitulé de la prestation de service", props.Text{Style: fontstyle.Bold})),
				col.New(3).Add(text.New("Prix HT", props.Text{Style: fontstyle.Bold, Align: align.Right})),
				col.New(3).Add(text.New("Prix TTC", props.Text{Style: fontstyle.Bold, Align: align.Right})),
			),
			row.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 150, Green: 150, Blue: 150}}))),
			row.New(5),
		)

		invoice.AddRows(
			row.New(10).Add(
				col.New(6).Add(text.New(serviceType)),
				col.New(3).Add(text.New(fmt.Sprintf("%.2f €", costHT), props.Text{Align: align.Right})),
				col.New(3).Add(text.New(fmt.Sprintf("%.2f €", cost), props.Text{Align: align.Right})),
			),
			row.New(10),
		)

		invoice.AddRows(
			row.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 0, Green: 0, Blue: 0}}))),
			row.New(5),
		)

		invoice.AddRows(
			row.New(10).Add(
				col.New(8).Add(text.New("TOTAL (TVA à 20%) :", props.Text{Style: fontstyle.Bold, Align: align.Right})),
				col.New(4).Add(text.New(fmt.Sprintf("%.2f €", cost), props.Text{Size: 12, Style: fontstyle.Bold, Align: align.Right})),
			),
			row.New(20),
		)

		invoice.AddRows(
			row.New(10).Add(
				col.New(12).Add(text.New("Merci pour votre confiance, l'équipe Silver Happy", props.Text{Align: align.Center, Style: fontstyle.Italic})),
			),
		)

		doc, err := invoice.Generate()
		if err != nil {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?&error=system1", 303)
			return
		}

		filename := fmt.Sprintf("invoice_service_%s_%s.pdf", idService, idConsumer)
		pathName := fmt.Sprintf("../data/invoices_services/%s", filename)

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

		_, insertExecError := insertStatement.Exec(idConsumer, identifier, "service", dateIdentifier, cost, filename)

		if insertExecError != nil {

			fmt.Println("Erreur d'insertion CONSUMER_INVOICE:", insertExecError)
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?error=system4", 303)
			return 

		}

		providerNewAmount := cost - cost * commission

		var idServiceProviderInvoice int
		var existingAmount float64
		var existingNbServices int

		currentMonth := time.Now().Month()
		currentYear := time.Now().Year()

		row := database.QueryRow("SELECT ID_SERVICE_PROVIDER_INVOICE, amount, nb_services_provided FROM SERVICE_PROVIDER_INVOICE WHERE ID_SERVICE_PROVIDER = ? AND month_billed = ? AND year_billed = ?", idServiceProvider, currentMonth, currentYear)
		
		err = row.Scan(&idServiceProviderInvoice, &existingAmount, &existingNbServices)

		if err == sql.ErrNoRows{

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

		}else if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?&error=system7", 303)	
			return 

		}else if err == nil{

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

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?&notif=paiement_success", 303)	

	}
	
}