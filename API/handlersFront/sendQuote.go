package handlersFront

import (
	"database/sql"
	"fmt"
	"math/rand/v2"
	"net/http"
	"os"
	"strconv"
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

func SendQuote(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		prix := strings.TrimSpace(r.FormValue("prix"))

		dateUnique := strings.TrimSpace(r.FormValue("dateUnique"))
		dateDebut := strings.TrimSpace(r.FormValue("dateDebut"))
		dateFin := strings.TrimSpace(r.FormValue("dateFin"))
		datePerso := strings.TrimSpace(r.FormValue("datePerso"))

		id := r.FormValue("id")
		idDiscussion := r.FormValue("id_discussion")
		idService := r.FormValue("id_service")

		url := fmt.Sprintf("http://localhost/ProjetAnnuel/quoteForm.php?id_discussion=%s", idDiscussion)

		if prix == ""{

			http.Redirect(w, r, url + "&error=missing_field", 303)
			return

		}

		modeUnique := dateUnique != ""
		modePeriode := dateDebut != "" && dateFin != ""
		modePerso := datePerso != ""

		if((modeUnique && (modePeriode || modePerso)) || (modePeriode && (modeUnique || modePerso)) || (modePerso && (modeUnique || modePeriode))){

			http.Redirect(w, r, url + "&error=two_dates_modes", 303)
			return		

		}

		if(!modeUnique && !modePeriode && !modePerso){

			http.Redirect(w, r, url + "&error=missing_field", 303)
			return

		}

		var idServiceProvider int
		var idConsumer int

		row := database.QueryRow("SELECT SERVICE_PROVIDER.ID_SERVICE_PROVIDER, CONSUMER.ID_CONSUMER FROM DISCUSSION INNER JOIN SERVICE_PROVIDER ON SERVICE_PROVIDER.ID_USER IN (DISCUSSION.user1_id, DISCUSSION.user2_id) INNER JOIN CONSUMER ON CONSUMER.ID_USER IN (DISCUSSION.user1_id, DISCUSSION.user2_id) WHERE DISCUSSION.ID_DISCUSSION = ?", idDiscussion)

		err := row.Scan(&idServiceProvider, &idConsumer)

		if err != nil {

			http.Redirect(w, r, url + "&error=system1", 303)
			return

		}

		var dateStartOrUnique interface{}
		var dateEnd interface{}
		var datePersonalized interface{}

		if modeUnique {

			dateStartOrUnique = dateUnique

		}else if modePeriode{

			dateStartOrUnique = dateDebut
			dateEnd = dateFin

		}else if modePerso{

			datePersonalized = datePerso

		}

		var prestation string

		rowPrestation :=database.QueryRow("SELECT type FROM SERVICE WHERE ID_SERVICE = ?", idService)

		errPrestation := rowPrestation.Scan(&prestation)

		if errPrestation != nil {
			http.Redirect(w, r, url + "&error=system", 303)
			return
		}

		randomNumber := rand.IntN(1000)

		filename := fmt.Sprintf("quote_service_%s__%d_%s_%d.pdf", idService, idConsumer, id, randomNumber)
		pathName := fmt.Sprintf("../data/quotes/%s", filename)

		insertStatement, insertError := database.Prepare("INSERT INTO QUOTE (ID_SERVICE_PROVIDER, ID_CONSUMER, ID_SERVICE, prestation, amount, date_start_or_unique, date_end, date_personalized, status, pdf_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?)")

		if insertError != nil {

			http.Redirect(w, r, url + "&error=system2", 303)
			return

		}
		defer insertStatement.Close()

		_, insertExecError := insertStatement.Exec(idServiceProvider, idConsumer, idService, prestation, prix, dateStartOrUnique, dateEnd, datePersonalized, filename)

		if insertExecError != nil {

			http.Redirect(w, r, url + "&error=system2", 303)
			return

		}

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
		var profession string
		var commission float64
		var city2 string
		var street2 string
		var nbStreet2 int
		var postalCode2 string
		var email2 string

		rowUser := database.QueryRow("SELECT USER_.ID_USER, USER_.name, USER_.surname, USER_.city, USER_.street, USER_.nb_street, USER_.postal_code, USER_.email FROM CONSUMER INNER JOIN USER_ ON CONSUMER.ID_USER = USER_.ID_USER WHERE CONSUMER.ID_CONSUMER = ?", idConsumer)
		
		errUser := rowUser.Scan(&idUser1, &name1, &surname1, &city1, &street1, &nbStreet1, &postalCode1, &email1)

		if errUser != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?&error=quote_error", 303)	
			return 

		}

		rowService := database.QueryRow("SELECT USER_.name, USER_.surname, SERVICE_PROVIDER.profession, SERVICE_PROVIDER.commission, USER_.city, USER_.street, USER_.nb_street, USER_.postal_code, USER_.email FROM SERVICE INNER JOIN OFFER ON SERVICE.ID_SERVICE = OFFER.ID_SERVICE INNER JOIN SERVICE_PROVIDER ON OFFER.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER WHERE SERVICE.ID_SERVICE = ?", idService)

        errService := rowService.Scan(&name2, &surname2, &profession, &commission, &city2, &street2, &nbStreet2, &postalCode2, &email2)

        if errService != nil {
            http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?error=quote_error", 303)
            return
        }

		dateQuoteGeneration := time.Now().Format(("02/01/2006"))

		floatPrice, _ := strconv.ParseFloat(prix, 64)

		finalPrice := float64(floatPrice) * 1.2 + floatPrice * commission
		finalPriceHT := floatPrice * commission

		dateIdentifier := time.Now().Format(("2006-01-02"))
		identifier := fmt.Sprintf("quote_%d_%s_%s%s", idConsumer, id, prestation, dateIdentifier)

		config := config.NewBuilder().WithPageSize(pagesize.A4).WithLeftMargin(10).WithRightMargin(10).WithTopMargin(10).WithBottomMargin(10).WithOrientation(orientation.Vertical).Build()
		quote := maroto.New(config)

		quote.AddRows(
			mRow.New(20).Add(
				col.New(4).Add(mImage.NewFromFile("../medias/logos/logoComplet.png")),
				col.New(8).Add(text.New("DEVIS - Prestation de service", props.Text{Size: 24, Style: fontstyle.Bold, Align: align.Right})),
			),
			mRow.New(15),
		)

		quote.AddRows(
			mRow.New(6).Add(
				col.New(6).Add(text.New(fmt.Sprintf("Date d'émission : %s", dateQuoteGeneration),
					props.Text{Style: fontstyle.Bold})),
				col.New(6).Add(text.New(fmt.Sprintf("Identifiant : %s", identifier),
					props.Text{Style: fontstyle.Bold, Align: align.Right})),
			),
			mRow.New(8),
		)

		quote.AddRows(
			mRow.New(6).Add(
				col.New(4).Add(text.New("Émetteur :", props.Text{Style: fontstyle.Bold})),
				col.New(4).Add(text.New("Prestataire :", props.Text{Style: fontstyle.Bold})),
				col.New(4).Add(text.New("Destinataire :", props.Text{Style: fontstyle.Bold})),
			),
			mRow.New(5).Add(
				col.New(4).Add(text.New("Silver Happy")),
				col.New(4).Add(text.New(fmt.Sprintf("%s %s", name2, surname2))),
				col.New(4).Add(text.New(fmt.Sprintf("%s %s", name1, surname1))),
			),
			mRow.New(5).Add(
				col.New(4).Add(text.New("244, rue du Faubourg Saint Antoine")),
				col.New(4).Add(text.New(fmt.Sprintf("%d, %s", nbStreet2, street2))),
				col.New(4).Add(text.New(fmt.Sprintf("%d, %s", nbStreet1, street1))),
			),
			mRow.New(5).Add(
				col.New(4).Add(text.New("75011, Paris")),
				col.New(4).Add(text.New(fmt.Sprintf("%s, %s", postalCode2, city2))),
				col.New(4).Add(text.New(fmt.Sprintf("%s, %s", postalCode1, city1))),
			),
			mRow.New(5).Add(
				col.New(4).Add(text.New("silverhappy@gmail.com")),
				col.New(4).Add(text.New(email2)),
				col.New(4).Add(text.New(email1)),
			),
			mRow.New(5).Add(
				col.New(4).Add(text.New("SIRET : 123 456 789 12345")),
				col.New(4).Add(text.New(fmt.Sprintf("Profession : %s", profession))),
			),
			mRow.New(10),
		)

		quote.AddRows(
			mRow.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 0, Green: 0, Blue: 0}}))), 
			mRow.New(10),
		)

		quote.AddRows(
			mRow.New(6).Add(
				col.New(6).Add(text.New("Intitulé de la prestation de service", props.Text{Style: fontstyle.Bold})),
				col.New(3).Add(text.New("Prix HT", props.Text{Style: fontstyle.Bold, Align: align.Right})),
				col.New(3).Add(text.New("Prix TTC", props.Text{Style: fontstyle.Bold, Align: align.Right})),
			),
			mRow.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 150, Green: 150, Blue: 150}}))),
			mRow.New(5),
		)

		quote.AddRows(
			mRow.New(10).Add(
				col.New(6).Add(text.New(prestation)),
				col.New(3).Add(text.New(fmt.Sprintf("%.2f €", finalPriceHT), props.Text{Align: align.Right})),
				col.New(3).Add(text.New(fmt.Sprintf("%.2f €", finalPrice), props.Text{Align: align.Right})),
			),
			mRow.New(10),
		)

		quote.AddRows(
			mRow.New(1).Add(col.New(12).Add(line.New(props.Line{Color: &props.Color{Red: 0, Green: 0, Blue: 0}}))),
			mRow.New(5),
		)

		quote.AddRows(
			mRow.New(10).Add(
				col.New(8).Add(text.New("TOTAL (TVA à 20%) :", props.Text{Style: fontstyle.Bold, Align: align.Right})),
				col.New(4).Add(text.New(fmt.Sprintf("%.2f €", finalPrice), props.Text{Size: 12, Style: fontstyle.Bold, Align: align.Right})),
			),
			mRow.New(20),
		)

		quote.AddRows(
			mRow.New(10).Add(
				col.New(12).Add(text.New("Merci pour votre confiance, l'équipe Silver Happy", props.Text{Align: align.Center, Style: fontstyle.Italic})),
			),
		)

		doc, err := quote.Generate()
		if err != nil {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/quoteForm.php?&error=quote_error", 303)
			return
		}

		err = os.WriteFile(pathName, doc.GetBytes(), 0644)

		if err != nil {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/quoteForm.php?&error=quote_error", 303)
			return
		}
		
		url = fmt.Sprintf("http://localhost/ProjetAnnuel/messaging.php?id_discussion=%s&notif=quote_sent", idDiscussion)
		http.Redirect(w, r, url, 303)

	}
	
}