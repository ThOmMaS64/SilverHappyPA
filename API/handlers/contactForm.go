package handlers

import (
	"database/sql"
	"net/http"
	"net/mail"
)

func ContactForm(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		if r.Method != "POST" {
		
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/contacts.php?error=system1", 303)
			return
		
		}

		errorParse := r.ParseForm()

		if errorParse != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/contacts.php?error=system2", 303)
			return

		}

		subject := r.FormValue("subject")
		email := r.FormValue("email")
		message := r.FormValue("message")

		if(subject == "" || email == "" || message == ""){

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/contacts.php?error=missing_field", 303)
			return 	

		}		

		_, errorFormatEmail := mail.ParseAddress(email)

		if (errorFormatEmail != nil) {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/contacts.php?error=invalid_email", 303)
			return

		}

		insertStatement, insertError := database.Prepare("INSERT INTO CONTACTS(subject, email, message) VALUES(?, ?, ?)")

		if insertError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/contacts.php?error=system3", 303)
			return	

		}
		defer insertStatement.Close()

		_, insertExecErrorAddress := insertStatement.Exec(subject, email, message)

		if insertExecErrorAddress != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/contacts.php?error=system4", 303)
			return	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/contacts.php?notif=message_sent", 303)
		 
	}

}