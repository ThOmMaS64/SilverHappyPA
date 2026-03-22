package handlersBack

import (
	"database/sql"
	"net/http"
)

func DeleteProduct(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")

		deleteStatement, deleteError := database.Prepare("DELETE FROM ORDER_LINE WHERE ID_PRODUCT = ?")

		if deleteError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageshop", 303)
			return	

		}
		defer deleteStatement.Close()

		_, deleteStatementExecError := deleteStatement.Exec(id)

		if deleteStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageshop", 303)
			return	

		}

		deleteStatement2, deleteError2 := database.Prepare("DELETE FROM PRODUCT WHERE ID_PRODUCT = ?")

		if deleteError2 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageshop", 303)
			return	

		}
		defer deleteStatement2.Close()

		_, deleteStatementExecError2 := deleteStatement2.Exec(id)

		if deleteStatementExecError2 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageshop", 303)
			return	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=delete_success#pageshop", 303)

	}

}