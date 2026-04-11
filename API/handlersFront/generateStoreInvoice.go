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

type orderLine struct {
	productName string
	quantity int
	priceUnit float64
	priceTotal float64
}

func GenerateStoreInvoice(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idConsumer := r.FormValue("id_consumer")
		idShopOrder := r.FormValue("id_shop_order")

		var idUser int
		var name string
		var surname string
		var city string
		var street string
		var nbStreet int
		var postalCode string
		var email string

		rowUser := database.QueryRow("SELECT USER_.ID_USER, USER_.name, USER_.surname, USER_.city, USER_.street, USER_.nb_street, USER_.postal_code, USER_.email FROM CONSUMER INNER JOIN USER_ ON CONSUMER.ID_USER = USER_.ID_USER WHERE CONSUMER.ID_CONSUMER = ?", idConsumer)
		
		errUser := rowUser.Scan(&idUser, &name, &surname, &city, &street, &nbStreet, &postalCode, &email)

		if errUser != nil {

			fmt.Println("ERREUR WriteFile:", errUser)
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?&error=invoice_error", 303)	
			return 

		}

		rowProducts, errProducts := database.Query(`
			SELECT PRODUCT.name, ORDER_LINE.quantity, PRODUCT.price
			FROM ORDER_LINE
			INNER JOIN PRODUCT ON ORDER_LINE.ID_PRODUCT = PRODUCT.ID_PRODUCT
			WHERE ORDER_LINE.ID_SHOP_ORDER = ?`, idShopOrder)

		if errProducts != nil {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=invoice_error", 303)
			return
		}
		defer rowProducts.Close()

		var lines[]orderLine
		var totalTTC float64

		for rowProducts.Next() {
			var ol orderLine
			if err := rowProducts.Scan(&ol.productName, &ol.quantity, &ol.priceUnit); err != nil {
				continue
			}
			ol.priceTotal = ol.priceUnit * float64(ol.quantity)
			totalTTC += ol.priceTotal
			lines = append(lines, ol)
		}

		if len(lines) == 0 {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=invoice_error", 303)
			return
		}

		date := time.Now().Format(("02/01/2006"))

		dateIdentifier := time.Now().Format(("02-01-2006"))
		identifier := "store_" + idConsumer + "_" + idShopOrder + "_" + dateIdentifier

		config := config.NewBuilder().WithPageSize(pagesize.A4).WithLeftMargin(10).WithRightMargin(10).WithTopMargin(10).WithBottomMargin(10).WithOrientation(orientation.Vertical).Build()
		invoice := maroto.New(config)

		invoice.AddRows(
			row.New(20).Add(
				col.New(4).Add(mImage.NewFromFile("../medias/logos/logoComplet.png")),
				col.New(8).Add(text.New("FACTURE - Commande boutique", props.Text{Size: 24, Style: fontstyle.Bold, Align: align.Right})),
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
				col.New(5).Add(text.New("Intitulé du produit", props.Text{Style: fontstyle.Bold})),
				col.New(2).Add(text.New("Quantité", props.Text{Style: fontstyle.Bold, Align: align.Right})),
				col.New(2).Add(text.New("Prix unitaire HT", props.Text{Style: fontstyle.Bold, Align: align.Right})),
				col.New(3).Add(text.New("Prix total TTC", props.Text{Style: fontstyle.Bold, Align: align.Right})),
			),
			row.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 150, Green: 150, Blue: 150}}))),
			row.New(5),
		)

		for _, ol := range lines {
			unitHT  := ol.priceUnit / 1.2
			invoice.AddRows(
				row.New(8).Add(
					col.New(5).Add(text.New(ol.productName)),
					col.New(2).Add(text.New(fmt.Sprintf("%d", ol.quantity),
						props.Text{Align: align.Center})),
					col.New(2).Add(text.New(fmt.Sprintf("%.2f €", unitHT),
						props.Text{Align: align.Right})),
					col.New(3).Add(text.New(fmt.Sprintf("%.2f €", ol.priceTotal),
						props.Text{Align: align.Right})),
				),
			)
		}

		invoice.AddRows(
			row.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 0, Green: 0, Blue: 0}}))),
			row.New(5),
		)

		invoice.AddRows(
			row.New(10).Add(
				col.New(8).Add(text.New("TOTAL (TVA à 20%) :", props.Text{Style: fontstyle.Bold, Align: align.Right})),
				col.New(4).Add(text.New(fmt.Sprintf("%.2f €", totalTTC), props.Text{Size: 12, Style: fontstyle.Bold, Align: align.Right})),
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
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?&error=invoice_error", 303)
			return
		}

		filename := fmt.Sprintf("invoice_store_%s_%s.pdf", idShopOrder, idConsumer)
		pathName := fmt.Sprintf("../data/invoices_store/%s", filename)

		err = os.WriteFile(pathName, doc.GetBytes(), 0644)

		if err != nil {
			fmt.Println("ERREUR WriteFile:", err)
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?&error=invoice_error", 303)
			return
		}

		insertStatement, insertError := database.Prepare("INSERT INTO CONSUMER_INVOICE(ID_CONSUMER, identifier, type, date_emission, amount, pdf_path) VALUES(?, ?, ?, ?, ?, ?)")

		if insertError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?notif=paiement_success&error=system1", 303)	
			return 

		}
		defer insertStatement.Close()

		res, insertExecError := insertStatement.Exec(idConsumer, identifier, "store", dateIdentifier, totalTTC, filename)

		if insertExecError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?notif=paiement_success&error=system2", 303)
			return 

		}

		idConsumerInvoice, _ := res.LastInsertId()

		updateStatement, updateError := database.Prepare("UPDATE SHOP_ORDER SET ID_CONSUMER_INVOICE = ? WHERE ID_SHOP_ORDER = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=system3", 303)
			return	

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(idConsumerInvoice, idShopOrder)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=system4", 303)
			return	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?&notif=paiement_success", 303)	

	}
	
}