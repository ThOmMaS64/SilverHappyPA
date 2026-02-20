package handlers

import (
	"database/sql"
	"net/http"

	"golang.org/x/crypto/bcrypt"

	"github.com/stripe/stripe-go/v79"
	"github.com/stripe/stripe-go/v79/subscription"
)

func ModifyParameters(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		if r.Method != "POST" {
		
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system1", 303)
			return
		
		}

		id := r.FormValue("id")
		paramChoice := r.FormValue("paramChoice")

		if paramChoice == "1" {

			colorChange := r.FormValue(("colorChange"))

			if colorChange == "1" {

				updateStatement, updateError := database.Prepare("UPDATE USER_ SET darkMode = ? WHERE ID_USER = ?")

				if updateError != nil{

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system2", 303)
					return	

				}
				defer updateStatement.Close()

				_, updateStatementExecError := updateStatement.Exec("1", id)

				if updateStatementExecError != nil{

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system3", 303)
					return	

				}

			}else if colorChange == "2"{

				updateStatement, updateError := database.Prepare("UPDATE USER_ SET darkMode = ? WHERE ID_USER = ?")

				if updateError != nil{

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system2", 303)
					return	

				}
				defer updateStatement.Close()

				_, updateStatementExecError := updateStatement.Exec("0", id)

				if updateStatementExecError != nil{

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system3", 303)
					return	

				}

			}

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?need=call_bdd_back&selectedParameter=1&notif=color_change_successful", 303)
			return	

		}else if paramChoice == "2" {

			password := r.FormValue(("password"))
			var dbPassword string

			row := database.QueryRow("SELECT password FROM user_ WHERE ID_USER = ?", id)
	
			err := row.Scan(&dbPassword)

			if err != nil {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system4", 303)
				return

			}

			errorComparisonPassword := bcrypt.CompareHashAndPassword([]byte(dbPassword), []byte(password))

			if errorComparisonPassword != nil {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=wrong_password", 303)
				return 

			}

			var stripeSubId sql.NullString

			rowStripe := database.QueryRow("SELECT stripe_subscription_id FROM USER_ WHERE ID_USER = ?", id)
			errorStripe := rowStripe.Scan(&stripeSubId)

			if errorStripe == nil && stripeSubId.Valid && stripeSubId.String != ""{

				stripe.Key = "sk_test_51Szo1M2WiLfhQi4sPPN6NYJI4gecl8Au5rA0oxHK7grJ6H4u41IReSoXwXn0NqrtzqWW8yXOItSD7MvuuW0q0Sgt009dm7h1pa"
				annulationParameter := &stripe.SubscriptionCancelParams{}
				
				_, errorStripeCancel := subscription.Cancel(stripeSubId.String, annulationParameter)	

				if errorStripeCancel != nil {

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=stripeCancel", 303)
						return 

				}

			}

			
			var idConsumer int
			var idProvider int

			row1 := database.QueryRow("SELECT ID_CONSUMER FROM CONSUMER WHERE ID_USER = ?", id)
			_ = row1.Scan(&idConsumer)

			row2 := database.QueryRow("SELECT ID_SERVICE_PROVIDER FROM SERVICE_PROVIDER WHERE ID_USER = ?", id)
			_ = row2.Scan(&idProvider)

			if idConsumer > 0 {

				queries := []string{

					"DELETE FROM CONTRACT WHERE ID_CONSUMER = ?",
					"DELETE FROM NOTIFICATION WHERE ID_CONSUMER = ?",
					"DELETE FROM GRADE WHERE ID_CONSUMER = ?",
					"DELETE FROM BUY WHERE ID_CONSUMER = ?",
					"DELETE FROM PARTICIPATE WHERE ID_CONSUMER = ?",
					"DELETE FROM CALL_ WHERE ID_CONSUMER = ?",
					"DELETE FROM CONSUMER WHERE ID_CONSUMER = ?",

				}

				for _, query := range queries {

					_, err := database.Exec(query, idConsumer)

					if err != nil {

						http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system5", 303)
						return 

					}

				}

			}
			
			if idProvider > 0 {

				queries := []string{

					"DELETE FROM ADVERTISEMENT WHERE ID_SERVICE_PROVIDER = ?",
					"DELETE FROM INVOICE WHERE ID_SERVICE_PROVIDER = ?",
					"DELETE FROM OFFER WHERE ID_SERVICE_PROVIDER = ?",
					"DELETE FROM DO WHERE ID_SERVICE_PROVIDER = ?",
					"DELETE FROM CONDUCT WHERE ID_SERVICE_PROVIDER = ?",
					"DELETE FROM SERVICE_PROVIDER WHERE ID_SERVICE_PROVIDER = ?",

				}
				
				for _, query := range queries {

					_, err := database.Exec(query, idProvider)

					if err != nil {

						http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system6", 303)
						return 

					}

				}

			}

			queriesUser := []string{

				"DELETE FROM MESSAGE WHERE ID_USER = ?",
				"DELETE FROM TOKEN WHERE ID_USER = ?",
				"DELETE FROM PLANNING WHERE ID_USER = ?",
				"DELETE FROM SUBSCRIBE WHERE ID_USER = ?",
				"DELETE FROM USER_ WHERE ID_USER = ?",

			}
				
			for _, query := range queriesUser {

				_, err := database.Exec(query, id)

				if err != nil {

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system6", 303)
					return 

				}

			}

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/deconnexion.php?notif=account_suppression", 303)
			return 

		}else if paramChoice == "3" {

			level := r.FormValue(("level"))

			updateStatement, updateError := database.Prepare("UPDATE USER_ SET levelFont = ? WHERE ID_USER = ?")

			if updateError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system2", 303)
				return	

			}
			defer updateStatement.Close()

			_, updateStatementExecError := updateStatement.Exec(level, id)

			if updateStatementExecError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system3", 303)
				return	

			}

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?need=call_bdd_back&selectedParameter=3&notif=font_size_changement_success", 303)
			return	

		}else if paramChoice == "4" {

			fontChange := r.FormValue("fontChange")

			if fontChange == "0" {

				fontChange = "1"

			}else if fontChange == "1"{

				fontChange = "0"

			}

			updateStatement, updateError := database.Prepare("UPDATE USER_ SET fontChange = ? WHERE ID_USER = ?")

			if updateError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system3", 303)
				return	

			}
			defer updateStatement.Close()

			_, updateStatementExecError := updateStatement.Exec(fontChange, id)

			if updateStatementExecError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system4", 303)
				return	

			}

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?need=call_bdd_back&selectedParameter=4&notif=font_style_changement_success", 303)
			return	

		}else if paramChoice == "5" {

			cursorType := r.FormValue("cursorType")

			updateStatement, updateError := database.Prepare("UPDATE USER_ SET cursorType = ? WHERE ID_USER = ?")

			if updateError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system8", 303)
				return	

			}
			defer updateStatement.Close()

			_, updateStatementExecError := updateStatement.Exec(cursorType, id)

			if updateStatementExecError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?error=system9", 303)
				return	

			}

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/parameters.php?need=call_bdd_back&selectedParameter=5&notif=cursor_type_changement_success", 303)
			return

		}
		 
	}

}