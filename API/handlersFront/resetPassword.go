package handlersFront

import (
	"database/sql"
	"net/http"

	"golang.org/x/crypto/bcrypt"
)

func ResetPassword(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		if r.Method != "POST" {
		
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/connexion.php?error=system1", 303)
			return
		
		}

		token := r.FormValue("token")
		password := r.FormValue("password")
		passwordConfirmation := r.FormValue("passwordConfirmation")

		var userID int

		rowToken := database.QueryRow("SELECT ID_USER FROM TOKEN WHERE token = ? AND token_date >= SUBTIME(NOW(),'01:00:00')", token)
	
		errToken := rowToken.Scan(&userID)

		if errToken == sql.ErrNoRows {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/connexion.php?error=retry_reset", 303)
			return

		} else if errToken != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/connexion.php?error=system3", 303)
			return

		}


		if len(password) < 8 || verifPassword(password, "ABCDEFGHIJKLMNOPQRSTUVWXYZ") == false || verifPassword(password, "0123456789") == false {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/resetPassword.php?error=bad_password&token="+token, 303)
			return

		}

		if (password != passwordConfirmation) {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/resetPassword.php?error=wrong_password_confirmation&token="+token, 303)
			return

		}

		var dbPassword string

		rowUser := database.QueryRow("SELECT password FROM user_ WHERE ID_USER = ?", userID)
	
		errUser := rowUser.Scan(&dbPassword)

		if errUser == nil {

			errSame := bcrypt.CompareHashAndPassword([]byte(dbPassword), []byte(password))

			if errSame == nil {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/resetPassword.php?error=same_password&token="+token, 303)
				return	

			}

		}

		hashedPassword, errorHash := bcrypt.GenerateFromPassword([]byte(password), bcrypt.DefaultCost)

		if errorHash != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system4&choice=1", 303)
			return	

		}

		updateStatement, updateError := database.Prepare("UPDATE USER_ SET password = ? WHERE ID_USER = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/inscription.php?error=system5&choice=1", 303)
			return	

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(hashedPassword, userID)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/resetPassword.php?error=system12&choice=1", 303)
			return	

		}

		deleteStatement, _ := database.Prepare("DELETE FROM TOKEN WHERE token = ?")
		_,_ = deleteStatement.Exec(token)

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/connexion.php?&notif=reset_success", 303)	

	}
	
}