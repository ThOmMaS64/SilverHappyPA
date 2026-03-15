package handlers

import (
	"database/sql"
	"fmt"
	"net/http"
	"net/mail"
	"net/url"
	"strings"
	"time"

	"golang.org/x/crypto/bcrypt"
)

func verifPassword(password string, searched string) bool {

	for _, char := range password {
		if strings.ContainsRune(searched, char){
			return true
		}
	}

	return false

}

func RegistrationCustomer(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		if r.Method != "POST" {
		
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/connexion.php?error=system1", 303)
			return
		
		}

		errorParse := r.ParseForm()

		if errorParse != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/connexion.php?error=missing_field", 303)
			return

		}

		username := r.FormValue("username")
		name := r.FormValue("name")
		surname := r.FormValue("surname")
		password := r.FormValue("password")
		passwordConfirmation := r.FormValue("passwordConfirmation")
		email := r.FormValue("email")
		birthdate := r.FormValue("dateNaissance")
		city := r.FormValue("ville")
		street := r.FormValue("rue")
		streetNumber := r.FormValue("numero")
		postalCode := r.FormValue("postalCode")
		captchaResponse := r.FormValue("captcha_response")
		captchaID := r.FormValue("captcha_id")

		if (username == "" || name == "" || surname == "" || password == "" || passwordConfirmation == "" || email == "" || birthdate == "" || city == "" || street == "" || streetNumber == "" || postalCode == "" || captchaResponse == "" || captchaID == ""){

			url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=missing_field&choice=1&username=%s&name=%s&surname=%s&email=%s&dateNaissance=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
				url.QueryEscape(username),
				url.QueryEscape(name),
				url.QueryEscape(surname),
				url.QueryEscape(email),
				url.QueryEscape(birthdate),
				url.QueryEscape(city),
				url.QueryEscape(street),
				url.QueryEscape(streetNumber),
				url.QueryEscape(postalCode))
		
			http.Redirect(w, r, url, 303)
			return

		}

		birthdateFmt, _ := time.Parse("2006-01-02", birthdate)

		if birthdateFmt.After(time.Now()) {

			url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=bad_birthdate&choice=1&username=%s&name=%s&surname=%s&email=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
				url.QueryEscape(username),
				url.QueryEscape(name),
				url.QueryEscape(surname),
				url.QueryEscape(email),
				url.QueryEscape(city),
				url.QueryEscape(street),
				url.QueryEscape(streetNumber),
				url.QueryEscape(postalCode))
		
			http.Redirect(w, r, url, 303)
			return

		}

		if time.Now().Year() - birthdateFmt.Year() < 14 || (time.Now().Year() == birthdateFmt.Year() && time.Now().Month() > birthdateFmt.Month()) || (time.Now().Year() == birthdateFmt.Year() && time.Now().Month() == birthdateFmt.Month() && time.Now().Day() > birthdateFmt.Day()) {

			url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=young&choice=1&username=%s&name=%s&surname=%s&email=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
				url.QueryEscape(username),
				url.QueryEscape(name),
				url.QueryEscape(surname),
				url.QueryEscape(email),
				url.QueryEscape(city),
				url.QueryEscape(street),
				url.QueryEscape(streetNumber),
				url.QueryEscape(postalCode))
		
			http.Redirect(w, r, url, 303)
			return

		}

		if len(password) < 8 || verifPassword(password, "ABCDEFGHIJKLMNOPQRSTUVWXYZ") == false || verifPassword(password, "0123456789") == false {

			url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=bad_password&choice=1&username=%s&name=%s&surname=%s&email=%s&dateNaissance=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
				url.QueryEscape(username),
				url.QueryEscape(name),
				url.QueryEscape(surname),
				url.QueryEscape(email),
				url.QueryEscape(birthdate),
				url.QueryEscape(city),
				url.QueryEscape(street),
				url.QueryEscape(streetNumber),
				url.QueryEscape(postalCode))
		
			http.Redirect(w, r, url, 303)
			return

		}

		if (password != passwordConfirmation) {

			url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=wrong_password_confirmation&choice=1&username=%s&name=%s&surname=%s&email=%s&dateNaissance=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
				url.QueryEscape(username),
				url.QueryEscape(name),
				url.QueryEscape(surname),
				url.QueryEscape(email),
				url.QueryEscape(birthdate),
				url.QueryEscape(city),
				url.QueryEscape(street),
				url.QueryEscape(streetNumber),
				url.QueryEscape(postalCode))
		
			http.Redirect(w, r, url, 303)
			return

		}

		_, errorFormatEmail := mail.ParseAddress(email)

		if (errorFormatEmail != nil) {

			url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=invalid_email&choice=1&username=%s&name=%s&surname=%s&dateNaissance=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
				url.QueryEscape(username),
				url.QueryEscape(name),
				url.QueryEscape(surname),
				url.QueryEscape(birthdate),
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

				url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=username_already_exists&choice=1&name=%s&surname=%s&email=%s&dateNaissance=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
					url.QueryEscape(name),
					url.QueryEscape(surname),
					url.QueryEscape(email),
					url.QueryEscape(birthdate),
					url.QueryEscape(city),
					url.QueryEscape(street),
					url.QueryEscape(streetNumber),
					url.QueryEscape(postalCode))
			
				http.Redirect(w, r, url, 303)
				return						

			}	

		}else if err != sql.ErrNoRows {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system2&choice=1", 303)
			return			

		}

		rowEmail := database.QueryRow("SELECT ID_USER, status FROM user_ WHERE email = ?", email)
	
		errEmail := rowEmail.Scan(&id, &status)

		if errEmail == nil {

			if status == -1 || status == -2 {

				database.Exec("DELETE FROM TOKEN WHERE ID_USER = ?", id)
				database.Exec("DELETE FROM CONSUMER WHERE ID_USER = ?", id)
				database.Exec("DELETE FROM SERVICE_PROVIDER WHERE ID_USER = ?", id)
				database.Exec("DELETE FROM USER_ WHERE ID_USER = ?", id)

			}else{

				url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=email_already_exists&choice=1&username=%s&name=%s&surname=%s&dateNaissance=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
					url.QueryEscape(username),
					url.QueryEscape(name),
					url.QueryEscape(surname),
					url.QueryEscape(birthdate),
					url.QueryEscape(city),
					url.QueryEscape(street),
					url.QueryEscape(streetNumber),
					url.QueryEscape(postalCode))
		
				http.Redirect(w, r, url, 303)
				return
			}			
		}else if errEmail != sql.ErrNoRows {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system3&choice=1", 303)
			return			

		}

		hashedPassword, errorHash := bcrypt.GenerateFromPassword([]byte(password), bcrypt.DefaultCost)

		if errorHash != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system4&choice=1", 303)
			return	

		}

		var goodResponseCaptcha string

		rowCaptcha := database.QueryRow("SELECT answer FROM CAPTCHA WHERE ID_CAPTCHA = ?", captchaID)
	
		errCaptcha := rowCaptcha.Scan(&goodResponseCaptcha)

		if errCaptcha != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system6&choice=1", 303)
			return			

		}

		if !strings.EqualFold(goodResponseCaptcha, captchaResponse) {

			url := fmt.Sprintf("http://localhost/ProjetAnnuel/inscription.php?error=wrong_captcha&choice=1&username=%s&name=%s&surname=%s&dateNaissance=%s&ville=%s&rue=%s&numero=%s&postalCode=%s", 
				url.QueryEscape(username),
				url.QueryEscape(name),
				url.QueryEscape(surname),
				url.QueryEscape(birthdate),
				url.QueryEscape(city),
				url.QueryEscape(street),
				url.QueryEscape(streetNumber),
				url.QueryEscape(postalCode))
		
			http.Redirect(w, r, url, 303)
			return	

		}

		insertStatementUser, insertErrorUser := database.Prepare("INSERT INTO USER_(username, password, email, name, surname, city, street, nb_street, postal_code, status, date_inscription) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")

		if insertErrorUser != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system9&choice=1", 303)
			return	

		}
		defer insertStatementUser.Close()

		date_inscription := time.Now().Format("2006-01-02")
		resUser, insertExecErrorUser := insertStatementUser.Exec(username, string(hashedPassword), email, name, surname, city, street, streetNumber, postalCode, -1, date_inscription)

		if insertExecErrorUser != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system10&choice=1", 303)
			return	

		}

		idUser, _ := resUser.LastInsertId()

		insertStatementConsumer, insertErrorConsumer := database.Prepare("INSERT INTO CONSUMER(birth_date, tuto_seen, ID_USER) VALUES(?, ?, ?)")

		if insertErrorConsumer != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system11&choice=1", 303)
			return	

		}
		defer insertStatementConsumer.Close()

		_, insertExecErrorCustomer := insertStatementConsumer.Exec(birthdate, 0, idUser)

		if insertExecErrorCustomer != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system12&choice=1", 303)
			return	

		}

		url := fmt.Sprintf("http://localhost/ProjetAnnuel/codeVerif.php?id=%d&email=%s&name=%s&status=-1", idUser, email, name)

		http.Redirect(w, r, url, 303)	

	}
}