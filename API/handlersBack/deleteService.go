package handlersBack

import (
	"database/sql"
	"net/http"
)

func DeleteService(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")

		deleteStatement, deleteError := database.Prepare("DELETE FROM OFFER WHERE ID_SERVICE = ?")

		if deleteError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}
		defer deleteStatement.Close()

		_, deleteStatementExecError := deleteStatement.Exec(id)

		if deleteStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}

		deleteStatement2, deleteError2 := database.Prepare("DELETE FROM QUOTE WHERE ID_SERVICE = ?")

		if deleteError2 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}
		defer deleteStatement2.Close()

		_, deleteStatementExecError2 := deleteStatement2.Exec(id)

		if deleteStatementExecError2 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}

		deleteStatement6, deleteError6 := database.Prepare("DELETE FROM DO WHERE ID_INTERVENTION IN (SELECT ID_INTERVENTION FROM INTERVENTION WHERE ID_SERVICE = ?)")

		if deleteError6 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}
		defer deleteStatement6.Close()

		_, deleteStatementExecError6 := deleteStatement6.Exec(id)

		if deleteStatementExecError6 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}

		deleteStatement7, deleteError7 := database.Prepare("DELETE FROM CALL_ WHERE ID_INTERVENTION IN (SELECT ID_INTERVENTION FROM INTERVENTION WHERE ID_SERVICE = ?)")

		if deleteError7 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}
		defer deleteStatement7.Close()

		_, deleteStatementExecError7 := deleteStatement7.Exec(id)

		if deleteStatementExecError7 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}

		deleteStatement5, deleteError5 := database.Prepare("DELETE FROM GRADE WHERE ID_INTERVENTION IN (SELECT ID_INTERVENTION FROM INTERVENTION WHERE ID_SERVICE = ?)")

		if deleteError5 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}
		defer deleteStatement5.Close()

		_, deleteStatementExecError5 := deleteStatement5.Exec(id)

		if deleteStatementExecError5 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}

		deleteStatement8, deleteError8 := database.Prepare("DELETE FROM PROVIDE WHERE ID_INTERVENTION IN (SELECT ID_INTERVENTION FROM INTERVENTION WHERE ID_SERVICE = ?) OR ID_QUOTE IN (SELECT ID_QUOTE FROM QUOTE WHERE ID_SERVICE = ?)")

		if deleteError8 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}
		defer deleteStatement8.Close()

		_, deleteStatementExecError8 := deleteStatement8.Exec(id, id)

		if deleteStatementExecError8 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}

		deleteStatement3, deleteError3 := database.Prepare("DELETE FROM INTERVENTION WHERE ID_SERVICE = ?")

		if deleteError3 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}
		defer deleteStatement3.Close()

		_, deleteStatementExecError3 := deleteStatement3.Exec(id)

		if deleteStatementExecError3 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}


		deleteStatement4, deleteError4 := database.Prepare("DELETE FROM SERVICE WHERE ID_SERVICE = ?")

		if deleteError4 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}
		defer deleteStatement4.Close()

		_, deleteStatementExecError4 := deleteStatement4.Exec(id)

		if deleteStatementExecError4 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=delete_error#pageservices", 303)
			return	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=delete_success#pageservices", 303)

	}

}