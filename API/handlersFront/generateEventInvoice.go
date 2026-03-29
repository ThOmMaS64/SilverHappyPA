package handlersFront

import (
	"database/sql"
	"fmt"
	"net/http"
	"os"

	"github.com/johnfercher/maroto/v2"
	"github.com/johnfercher/maroto/v2/pkg/components/col"
	mImage "github.com/johnfercher/maroto/v2/pkg/components/image"
	"github.com/johnfercher/maroto/v2/pkg/components/line"
	"github.com/johnfercher/maroto/v2/pkg/components/row"
	"github.com/johnfercher/maroto/v2/pkg/components/text"
	"github.com/johnfercher/maroto/v2/pkg/config"
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

			fmt.Println("ERREUR WriteFile:", errUser)
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/events.php?&error=invoice_error", 303)	
			return 

		}	

		config := config.NewBuilder().WithPageSize(pagesize.A4).WithLeftMargin(10).WithRightMargin(10).WithTopMargin(10).WithBottomMargin(10).WithOrientation(orientation.Vertical).Build()
		invoice := maroto.New(config)

		invoice.AddRows(
			row.New(30).Add(
				col.New(4).Add(
					mImage.NewFromFile("../medias/logos/logoComplet.png"),
				),
				col.New(8).Add(
					text.New("FACTURE - INSCRIPTION ÉVÉNEMENT"),
				),
			),
		)

		invoice.AddRows(
			row.New(2).Add(
				col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 255, Green: 255, Blue: 255}})),
			),
		)

		invoice.AddRows(
			row.New(6).Add(
				col.New(12).Add(
					text.New("Information - Silver Happy :"),
				),
			),
			row.New(4).Add(
				col.New(12).Add(
					text.New("Addresse postale :  244, rue du Faubourg Saint Antoine, 75011, Paris"),
				),
			),
			row.New(4).Add(
				col.New(12).Add(
					text.New("Addresse email : silverhappy@gmail.com"),
				),
			),

			row.New(6).Add(
				col.New(12).Add(
					text.New("Information - Client :"),
				),
			),
			row.New(4).Add(
				col.New(12).Add(
					text.New(fmt.Sprintf("%s %s", name, surname)),
				),
			),
			row.New(4).Add(
				col.New(12).Add(
					text.New(fmt.Sprintf("%d, %s, %s, %s", nbStreet, street, postalCode, city)),
				),
			),
			row.New(4).Add(
				col.New(12).Add(
					text.New(fmt.Sprintf("Addresse email : %s", email)),
				),
			),

		)

		invoice.AddRows(
			row.New(2).Add(
				col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 255, Green: 255, Blue: 255}})),
			),
		)
		invoice.AddRows(
			row.New(2).Add(
				col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 255, Green: 255, Blue: 255}})),
			),
		)

		invoice.AddRows(
			row.New(6).Add(
				col.New(12).Add(
					text.New("Commande :"),
				),
			),

			row.New(4).Add(
				col.New(6).Add(
					text.New(fmt.Sprintf("%s", nameEvent)),
				),
				col.New(6).Add(
					text.New(fmt.Sprintf("%.2f", price)),
				),
			),
		)

		invoice.AddRows(
			row.New(6).Add(
				col.New(12).Add(
					text.New(fmt.Sprintf("TOTAL : %.2f€ (TVA à 20%%)", price)),
				),
			),
		)

		invoice.AddRows(
			row.New(4).Add(
				col.New(12).Add(
					text.New("Merci pour votre confiance, Silver Happy"),
				),
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
			fmt.Println("ERREUR WriteFile:", err)
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/events.php?&error=invoice_error", 303)
			return
		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/events.php?&notif=paiement_success", 303)	

	}
	
}