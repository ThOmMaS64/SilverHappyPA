package handlersFront

import (
	"database/sql"
	"fmt"
	"io"
	"net/http"
	"net/mail"
	"net/url"
	"os"
	"strings"
	"time"

	"golang.org/x/crypto/bcrypt"
)

func saveDocument(file []byte, docType string, username string, database *sql.DB, idServiceProvider int){
	if len(file) == 0{
		return
	}

	filename := fmt.Sprintf("%s_%s.pdf", docType, username)
	pathname := "../data/documents/" + filename

	out, err := os.Create(pathname)

	if err != nil {
		return
	}
	defer out.Close()

	_, err = out.Write(file)
	if err != nil {
		return
	}

	insertStatementDoc, insertErrorDoc := database.Prepare("INSERT INTO SERVICE_PROVIDER_DOCUMENT(type, docPath, uploadDate, ID_SERVICE_PROVIDER) VALUES(?, ?, ?, ?)")

	if insertErrorDoc != nil{
		return	
	}
	defer insertStatementDoc.Close()

	uploadDate := time.Now().Format("2006-01-02")

	_, insertExecErrorCustomer := insertStatementDoc.Exec(docType, filename, uploadDate, idServiceProvider)

	if insertExecErrorCustomer != nil{
		return	
	}
}

func RegistrationProvider(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		if r.Method != "POST" {
		
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/connexion.php?error=system1&choice=2", 303)
			return
		
		}

		errorParse := r.ParseMultipartForm(10 << 20)

		if errorParse != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/connexion.php?error=missing_field&choice=2", 303)
			return

		}

		username := strings.ToLower(strings.TrimSpace(r.FormValue("username")))
		name := capitalizeFirst(strings.TrimSpace(r.FormValue("name")))
		surname := capitalizeFirst(strings.TrimSpace(r.FormValue("surname")))
		password := r.FormValue("password")
		passwordConfirmation := r.FormValue("passwordConfirmation")
		email := strings.ToLower(strings.TrimSpace(r.FormValue("email")))
		profession := strings.ToLower(strings.TrimSpace(r.FormValue("profession")))
		city := capitalizeFirst(strings.TrimSpace(r.FormValue("ville")))
		street := capitalizeFirst(strings.TrimSpace(r.FormValue("rue")))
		streetNumber := r.FormValue("numero")
		postalCode := r.FormValue("postalCode")
		captchaResponse := r.FormValue("captcha_response")
		captchaID := r.FormValue("captcha_id")		

		file2, _, _ := r.FormFile("criminalRecord")
		var criminalrecordBlob []byte
		if file2 != nil {
			defer file2.Close()
			criminalrecordBlob, _ = io.ReadAll(file2)
		}

		if (username == "" || name == "" || surname == "" || profession == "" || password == "" || passwordConfirmation == "" || email == "" || city == "" || street == "" || streetNumber == "" || postalCode == "" || criminalrecordBlob == nil || captchaResponse == "" || captchaID == ""){

			url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=missing_field&choice=2&username=%s&name=%s&surname=%s&email=%s&profession=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
				url.QueryEscape(username),
				url.QueryEscape(name),
				url.QueryEscape(surname),
				url.QueryEscape(email),
				url.QueryEscape(profession),
				url.QueryEscape(city),
				url.QueryEscape(street),
				url.QueryEscape(streetNumber),
				url.QueryEscape(postalCode))
		
			http.Redirect(w, r, url, 303)
			return

		}

		if (len(password) < 8 || verifPassword(password, "ABCDEFGHIJKLMNOPQRSTUVWXYZ") == false ||  verifPassword(password, "0123456789") == false) {

			url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=bad_password&choice=2&username=%s&name=%s&surname=%s&email=%s&profession=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
				url.QueryEscape(username),
				url.QueryEscape(name),
				url.QueryEscape(surname),
				url.QueryEscape(email),
				url.QueryEscape(profession),
				url.QueryEscape(city),
				url.QueryEscape(street),
				url.QueryEscape(streetNumber),
				url.QueryEscape(postalCode))
		
			http.Redirect(w, r, url, 303)
			return

		}

		if (password != passwordConfirmation) {

			url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=wrong_password_confirmation&choice=2&username=%s&name=%s&surname=%s&email=%s&profession=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
				url.QueryEscape(username),
				url.QueryEscape(name),
				url.QueryEscape(surname),
				url.QueryEscape(email),
				url.QueryEscape(profession),
				url.QueryEscape(city),
				url.QueryEscape(street),
				url.QueryEscape(streetNumber),
				url.QueryEscape(postalCode))
		
			http.Redirect(w, r, url, 303)
			return

		}

		_, errorFormatEmail := mail.ParseAddress(email)

		if (errorFormatEmail != nil) {

			url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=invalid_email&choice=2&username=%s&name=%s&surname=%s&profession=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
				url.QueryEscape(username),
				url.QueryEscape(name),
				url.QueryEscape(surname),
				url.QueryEscape(profession),
				url.QueryEscape(city),
				url.QueryEscape(street),
				url.QueryEscape(streetNumber),
				url.QueryEscape(postalCode))
		
			http.Redirect(w, r, url, 303)
			return

		}

		var id int
		var status int

		row := database.QueryRow("SELECT ID_USER, status FROM user_ WHERE username = ?", username)
	
		err := row.Scan(&id, &status)

		if err == nil {

			if (status == -1 || status == -2) {

				database.Exec("DELETE FROM CONSUMER WHERE ID_USER = ?", id)
				database.Exec("DELETE FROM SERVICE_PROVIDER WHERE ID_USER = ?", id)
				database.Exec("DELETE FROM USER_ WHERE ID_USER = ?", id)

			} else {

				url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=username_already_exists&choice=2&name=%s&surname=%s&email=%s&profession=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
					url.QueryEscape(name),
					url.QueryEscape(surname),
					url.QueryEscape(email),
					url.QueryEscape(profession),
					url.QueryEscape(city),
					url.QueryEscape(street),
					url.QueryEscape(streetNumber),
					url.QueryEscape(postalCode))
			
				http.Redirect(w, r, url, 303)
				return						

			}	

		}else if err != sql.ErrNoRows {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system2&choice=2", 303)
			return			

		}

		rowEmail := database.QueryRow("SELECT ID_USER, status FROM user_ WHERE email = ?", email)
	
		errEmail := rowEmail.Scan(&id, &status)

		if errEmail == nil {

			if status == -1 || status == -2 {

				database.Exec("DELETE FROM CONSUMER WHERE ID_USER = ?", id)
				database.Exec("DELETE FROM SERVICE_PROVIDER WHERE ID_USER = ?", id)
				database.Exec("DELETE FROM USER_ WHERE ID_USER = ?", id)

			}else{
				
				url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=email_already_exists&choice=2&username=%s&name=%s&surname=%s&profession=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
					url.QueryEscape(username),
					url.QueryEscape(name),
					url.QueryEscape(surname),
					url.QueryEscape(profession),
					url.QueryEscape(city),
					url.QueryEscape(street),
					url.QueryEscape(streetNumber),
					url.QueryEscape(postalCode))
			
				http.Redirect(w, r, url, 303)
				return
			}			
		}else if errEmail != sql.ErrNoRows {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system3&choice=2", 303)
			return			

		}

		hashedPassword, errorHash := bcrypt.GenerateFromPassword([]byte(password), bcrypt.DefaultCost)

		if errorHash != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system4&choice=2", 303)
			return	

		}

		var goodResponseCaptcha string

		rowCaptcha := database.QueryRow("SELECT answer FROM CAPTCHA WHERE ID_CAPTCHA = ?", captchaID)
	
		errCaptcha := rowCaptcha.Scan(&goodResponseCaptcha)

		if errCaptcha != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system6&choice=2", 303)
			return			

		}

		if !strings.EqualFold(goodResponseCaptcha, captchaResponse) {

			url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=wrong_captcha&choice=2&username=%s&name=%s&surname=%s&email=%s&profession=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
				url.QueryEscape(username),
				url.QueryEscape(name),
				url.QueryEscape(surname),
				url.QueryEscape(email),
				url.QueryEscape(profession),
				url.QueryEscape(city),
				url.QueryEscape(street),
				url.QueryEscape(streetNumber),
				url.QueryEscape(postalCode))
		
			http.Redirect(w, r, url, 303)
			return	

		}

		insertStatementUser, insertErrorUser := database.Prepare("INSERT INTO USER_(username, password, email, name, surname, city, street, nb_street, postal_code, status, date_inscription) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")

		if insertErrorUser != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system7&choice=2", 303)
			return	

		}
		defer insertStatementUser.Close()

		date_inscription := time.Now().Format("2006-01-02")
		resUser, insertExecErrorUser := insertStatementUser.Exec(username, string(hashedPassword), email, name, surname, city, street, streetNumber, postalCode, -2, date_inscription)

		if insertExecErrorUser != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system8&choice=2", 303)
			return	

		}

		idUser, _ := resUser.LastInsertId()

		insertStatementConsumer, insertErrorConsumer := database.Prepare("INSERT INTO SERVICE_PROVIDER(profession, ID_USER) VALUES(?, ?)")

		if insertErrorConsumer != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system9&choice=2", 303)
			return	

		}
		defer insertStatementConsumer.Close()

		resServiceProvider, insertExecErrorCustomer := insertStatementConsumer.Exec(profession, idUser)

		if insertExecErrorCustomer != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system10&choice=2", 303)
			return	

		}

		idServiceProvider, _ := resServiceProvider.LastInsertId()

		saveDocument(criminalrecordBlob, "criminal_record", username, database, int(idServiceProvider))

		url := fmt.Sprintf("http://localhost/ProjetAnnuel/codeVerif.php?id=%d&email=%s&name=%s&status=-2", idUser, email, name)

		http.Redirect(w, r, url, 303)	

	}
}