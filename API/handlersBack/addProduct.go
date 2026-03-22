package handlersBack

import (
	"database/sql"
	"net/http"
)

func AddProduct(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		name:= r.FormValue("name")
		prodType:= r.FormValue("type")
		description:= r.FormValue("description")
		price:= r.FormValue("price")

		insertStatement, insertError := database.Prepare("INSERT INTO PRODUCT(name, type, description, price) VALUES(?, ?, ?, ?)")

		if insertError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pageshop", 303)
			return 

		}
		defer insertStatement.Close()

		_, insertStatementExecError := insertStatement.Exec(name, prodType, description, price)

		if insertStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pageshop", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=add_success#pageshop", 303)

	}

}