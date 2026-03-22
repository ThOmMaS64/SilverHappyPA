package handlersBack

import (
	"database/sql"
	"net/http"
)

func UpdateProductData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		name:= r.FormValue("name")
		prodType:= r.FormValue("type")
		description := r.FormValue("description")

		updateStatement, updateError := database.Prepare("UPDATE PRODUCT SET name = ?, type = ?, description = ? WHERE ID_PRODUCT = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pageshop", 303)
			return 

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(name, prodType, description, id)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=update_error#pageshop", 303)
			return 	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=update_success#pageshop", 303)

	}

}