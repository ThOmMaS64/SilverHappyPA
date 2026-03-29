package handlersBack

import (
	"crypto/rand"
	"database/sql"
	"encoding/hex"
	"fmt"
	"net/http"
	"strings"

	"golang.org/x/crypto/bcrypt"
)

func GenerateSecureToken(length int) string {
    b := make([]byte, length)
    if _, err := rand.Read(b); err != nil {
        return ""
    }
    return hex.EncodeToString(b)
}


func LoginBack(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		if r.Method != "POST" {
		
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=system1", 303)
			return
		
		}

		errorParse := r.ParseForm()

		if errorParse != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=system2", 303)
			return

		}

		username := strings.ToLower(strings.TrimSpace(r.FormValue("username")))
		password := r.FormValue("password")

		var dbPassword string
		var userID int
		var status int

		row := database.QueryRow("SELECT ID_USER, password, status FROM user_ WHERE username = ? OR email = ?", username, username)
	
		err := row.Scan(&userID, &dbPassword, &status)

		if err == sql.ErrNoRows {

			http.Redirect(w, r, "hhttp://localhost/ProjetAnnuel/backoffice/index.php?error=not_registered", 303)
			return

		} else if status != 9 {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=bad_registration", 303)
			return			

		} else if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=system3", 303)
			return

		}

		errorComparisonPassword := bcrypt.CompareHashAndPassword([]byte(dbPassword), []byte(password))

		if errorComparisonPassword != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=wrong_password", 303)
			return 

		} 

		updateStatement, updateError := database.Prepare("UPDATE USER_ SET last_connection = NOW(), connected = 1 WHERE ID_USER = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=system4", 303)
			return 			

		}
		defer updateStatement.Close()

		_, updateExecError := updateStatement.Exec(userID)

		if updateExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=system5", 303)
			return 					

		}

		token := GenerateSecureToken(32)

		insertTokenStatement, insertTokenError := database.Prepare("INSERT INTO TOKEN(token, ID_USER, token_date) VALUES (?, ?, NOW())")

		if insertTokenError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=system5", 303)
			return 		

		}
		defer insertTokenStatement.Close()
	
		_, insertTokenExecError := insertTokenStatement.Exec(token, userID)

		if insertTokenExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=system6", 303)
			return 		

		}

		url := fmt.Sprintf("http://localhost/ProjetAnnuel/traitementsPHP/putInSessionBack.php?token=%s", token)

		http.Redirect(w, r, url, 303)
		 
	}

}