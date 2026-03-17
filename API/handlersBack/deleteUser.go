package handlersBack

import (
	"database/sql"
	"net/http"

	"github.com/stripe/stripe-go/v79"
	"github.com/stripe/stripe-go/v79/subscription"
)

func DeleteUser(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		status := r.FormValue("status")

		var stripeSubId sql.NullString

		rowStripe := database.QueryRow("SELECT stripe_subscription_id FROM CONSUMER WHERE ID_USER = ?", id)
		errorStripe := rowStripe.Scan(&stripeSubId)

		if errorStripe == nil && stripeSubId.Valid && stripeSubId.String != ""{

			stripe.Key = "sk_test_51Szo1M2WiLfhQi4sPPN6NYJI4gecl8Au5rA0oxHK7grJ6H4u41IReSoXwXn0NqrtzqWW8yXOItSD7MvuuW0q0Sgt009dm7h1pa"
			annulationParameter := &stripe.SubscriptionCancelParams{}
				
			_, errorStripeCancel := subscription.Cancel(stripeSubId.String, annulationParameter)	

			if errorStripeCancel != nil {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php#pageusers", 303)
				return 

			}

		}

		if status == "-1" || status == "1" || status == "2" || status == "5" || status == "6"{

			var idConsumer int

			row1 := database.QueryRow("SELECT ID_CONSUMER FROM CONSUMER WHERE ID_USER = ?", id)
			_ = row1.Scan(&idConsumer)

			queries := []string{

				"DELETE FROM CONTRACT WHERE ID_CONSUMER = ?",
				"DELETE FROM NOTIFICATION WHERE ID_CONSUMER = ?",
				"DELETE FROM GRADE WHERE ID_CONSUMER = ?",
				"DELETE FROM PARTICIPATE WHERE ID_CONSUMER = ?",
				"DELETE FROM CALL_ WHERE ID_CONSUMER = ?",
				"DELETE FROM ORDER_LINE WHERE ID_SHOP_ORDER IN (SELECT ID_SHOP_ORDER FROM SHOP_ORDER WHERE ID_CONSUMER = ?)",
				"DELETE FROM SHOP_ORDER WHERE ID_CONSUMER = ?",
				"DELETE FROM PROVIDE WHERE ID_QUOTE IN (SELECT ID_QUOTE FROM QUOTE WHERE ID_CONSUMER = ?)",
				"DELETE FROM QUOTE WHERE ID_CONSUMER = ?",
				"DELETE FROM CONSUMER WHERE ID_CONSUMER = ?",

			}

			for _, query := range queries {

				_, err := database.Exec(query, idConsumer)

				if err != nil {

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageusers", 303)
					return 

				}

			}

		}else if status == "-2" || status == "3" || status == "4" {

			var idProvider int

			row2 := database.QueryRow("SELECT ID_SERVICE_PROVIDER FROM SERVICE_PROVIDER WHERE ID_USER = ?", id)
			_ = row2.Scan(&idProvider)

			queries := []string{

				"DELETE FROM ADVERTISEMENT WHERE ID_SERVICE_PROVIDER = ?",
				"DELETE FROM INVOICE WHERE ID_SERVICE_PROVIDER = ?",
				"DELETE FROM OFFER WHERE ID_SERVICE_PROVIDER = ?",
				"DELETE FROM DO WHERE ID_SERVICE_PROVIDER = ?",
				"DELETE FROM CONDUCT WHERE ID_SERVICE_PROVIDER = ?",
				"DELETE FROM SERVICE_PROVIDER_DOCUMENT WHERE ID_SERVICE_PROVIDER = ?",
				"DELETE FROM PROVIDE WHERE ID_QUOTE IN (SELECT ID_QUOTE FROM QUOTE WHERE ID_SERVICE_PROVIDER = ?)",
				"DELETE FROM QUOTE WHERE ID_SERVICE_PROVIDER = ?",
				"DELETE FROM USER_INTERACTION_ADVICE WHERE ID_ADVICE IN (SELECT ID_ADVICE FROM ADVICE WHERE ID_SERVICE_PROVIDER = ?)",
				"DELETE FROM ADVICE WHERE ID_SERVICE_PROVIDER = ?",
				"DELETE FROM SERVICE_PROVIDER WHERE ID_SERVICE_PROVIDER = ?",

			}
				
			for _, query := range queries {

				_, err := database.Exec(query, idProvider)

				if err != nil {

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageusers", 303)
					return 

				}

			}

		}

		queriesUser := []string{

			"DELETE FROM MESSAGE WHERE sender_id = ? OR receiver_id = ?",
			"DELETE FROM TOKEN WHERE ID_USER = ?",
			"DELETE FROM PLANNING WHERE ID_USER = ?",
			"DELETE FROM USER_INTERACTION_ADVICE WHERE ID_USER = ?",
			"DELETE FROM USER_ WHERE ID_USER = ?",

		}
				
		for _, query := range queriesUser {

			if query == "DELETE FROM MESSAGE WHERE sender_id = ? OR receiver_id = ?" {

				_, err := database.Exec(query, id, id)

				if err != nil {

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageusers", 303)
					return 

				}

			}else{

				_, err := database.Exec(query, id)

				if err != nil {

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageusers", 303)
					return 

				}

			}

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=delete_success#pageusers", 303)

	}

}