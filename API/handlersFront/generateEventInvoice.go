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
func GenerateEventInvoice(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idConsumer := r.FormValue("id_consumer")
		idEvent := r.FormValue("id_event")

		var idUser int
		var name string
		var surname string
		var city string
		var street string
		var nbStreet int
		var postalCode string
		var email string
		var nameEvent string
		var price float64

		rowUser := database.QueryRow("SELECT USER_.ID_USER, USER_.name, USER_.surname, USER_.city, USER_.street, USER_.nb_street, USER_.postal_code, USER_.email, EVENT.name, EVENT.price FROM CONSUMER INNER JOIN USER_ ON CONSUMER.ID_USER = USER_.ID_USER INNER JOIN PARTICIPATE ON CONSUMER.ID_CONSUMER = PARTICIPATE.ID_CONSUMER INNER JOIN EVENT ON PARTICIPATE.ID_EVENT = EVENT.ID_EVENT WHERE CONSUMER.ID_CONSUMER = ? AND EVENT.ID_EVENT = ?", idConsumer, idEvent)
		
		errUser := rowUser.Scan(&idUser, &name, &surname, &city, &street, &nbStreet, &postalCode, &email, &nameEvent, &price)

		if errUser != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/events.php?&error=invoice_error", 303)	
			return 

		}

		date := time.Now().Format(("02/01/2006"))

		priceHT := price / 1.2

		dateIdentifier := time.Now().Format(("2006-01-02"))
		identifier := "event_" + idConsumer + "_" + idEvent + "_" + dateIdentifier

		config := config.NewBuilder().WithPageSize(pagesize.A4).WithLeftMargin(10).WithRightMargin(10).WithTopMargin(10).WithBottomMargin(10).WithOrientation(orientation.Vertical).Build()
		invoice := maroto.New(config)

		invoice.AddRows(
			row.New(20).Add(
				col.New(4).Add(mImage.NewFromFile("../medias/logos/logoComplet.png")),
				col.New(8).Add(text.New("FACTURE - Inscription événement", props.Text{Size: 24, Style: fontstyle.Bold, Align: align.Right})),
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
				col.New(6).Add(text.New("Émetteur :", props.Text{Style: fontstyle.Bold})),
				col.New(6).Add(text.New("Destinataire :", props.Text{Style: fontstyle.Bold})),
			),
			row.New(5).Add(
				col.New(6).Add(text.New("Silver Happy")),
				col.New(6).Add(text.New(fmt.Sprintf("%s %s", name, surname))),
			),
			row.New(5).Add(
				col.New(6).Add(text.New("244, rue du Faubourg Saint Antoine")),
				col.New(6).Add(text.New(fmt.Sprintf("%d, %s", nbStreet, street))),
			),
			row.New(5).Add(
				col.New(6).Add(text.New("75011, Paris")),
				col.New(6).Add(text.New(fmt.Sprintf("%s, %s", postalCode, city))),
			),
			row.New(5).Add(
				col.New(6).Add(text.New("silverhappy@gmail.com")),
				col.New(6).Add(text.New(email)),
			),
			row.New(5).Add(
				col.New(6).Add(text.New("SIRET : 123 456 789 12345")),
			),
			row.New(10),
		)

		invoice.AddRows(
			row.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 0, Green: 0, Blue: 0}}))), 
			row.New(10),
		)

		invoice.AddRows(
			row.New(6).Add(
				col.New(6).Add(text.New("Intitulé de l'événement", props.Text{Style: fontstyle.Bold})),
				col.New(3).Add(text.New("Prix HT", props.Text{Style: fontstyle.Bold, Align: align.Right})),
				col.New(3).Add(text.New("Prix TTC", props.Text{Style: fontstyle.Bold, Align: align.Right})),
			),
			row.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 150, Green: 150, Blue: 150}}))),
			row.New(5),
		)

		invoice.AddRows(
			row.New(10).Add(
				col.New(6).Add(text.New(nameEvent)),
				col.New(3).Add(text.New(fmt.Sprintf("%.2f €", priceHT), props.Text{Align: align.Right})),
				col.New(3).Add(text.New(fmt.Sprintf("%.2f €", price), props.Text{Align: align.Right})),
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
				col.New(4).Add(text.New(fmt.Sprintf("%.2f €", price), props.Text{Size: 12, Style: fontstyle.Bold, Align: align.Right})),
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
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/events.php?&error=invoice_error", 303)
			return
		}

		filename := fmt.Sprintf("invoice_event_%s_%s.pdf", idEvent, idConsumer)
		pathName := fmt.Sprintf("../data/invoices_events/%s", filename)

		err = os.WriteFile(pathName, doc.GetBytes(), 0644)

		if err != nil {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/events.php?&error=invoice_error", 303)
			return
		}

		insertStatement, insertError := database.Prepare("INSERT INTO CONSUMER_INVOICE(ID_CONSUMER, identifier, type, date_emission, amount, pdf_path) VALUES(?, ?, ?, ?, ?, ?)")

		if insertError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/events.php?notif=paiement_success&error=system1", 303)	
			return 

		}
		defer insertStatement.Close()

		res, insertExecError := insertStatement.Exec(idConsumer, identifier, "event", dateIdentifier, price, filename)

		if insertExecError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/events.php?notif=paiement_success&error=system2", 303)
			return 

		}

		idConsumerInvoice, _ := res.LastInsertId()

		updateStatement, updateError := database.Prepare("UPDATE PARTICIPATE SET ID_CONSUMER_INVOICE = ? WHERE ID_CONSUMER = ? AND ID_EVENT = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/events.php?error=system3", 303)
			return	

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(idConsumerInvoice, idConsumer, idEvent)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/events.php?error=system4", 303)
			return	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/events.php?&notif=paiement_success", 303)	

	}
	
}