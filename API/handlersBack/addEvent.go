package handlersBack

import (
	"database/sql"
	"net/http"
)

func AddEvent(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		eventType:= r.FormValue("type")
		name:= r.FormValue("name")
		date_start:= r.FormValue("date_start")
		date_end:= r.FormValue("date_end")
		description:= r.FormValue("description")
		price:= r.FormValue("price")
		capacity:= r.FormValue("capacity")

		city:= r.FormValue("city")
		street:= r.FormValue("street")
		nb_street:= r.FormValue("nb_street")
		postal_code:= r.FormValue("postal_code")

		var id_work_address int64

		rowAddress := database.QueryRow("SELECT ID_WORK_ADDRESS FROM WORK_ADDRESS WHERE city = ? AND street = ? AND nb_street = ? AND postal_code = ?", city, street, nb_street, postal_code)
	
		errAddress := rowAddress.Scan(&id_work_address)

		if errAddress == sql.ErrNoRows {

			insertStatement, insertError := database.Prepare("INSERT INTO WORK_ADDRESS(city, street, nb_street, postal_code) VALUES(?, ?, ?, ?)")

			if insertError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pageevents", 303)
				return 

			}
			defer insertStatement.Close()

			insertStatementExec, insertStatementExecError := insertStatement.Exec(city, street, nb_street, postal_code)

			if insertStatementExecError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pageevents", 303)
				return

			}

			id_work_address, _ = insertStatementExec.LastInsertId()

		}else if errAddress != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pageevents", 303)
			return

		}

		insertStatement, insertError := database.Prepare("INSERT INTO EVENT(type, name, date_start, date_end, description, price, capacity, ID_WORK_ADDRESS) VALUES(?, ?, ?, ?, ?, ?, ?, ?)")

		if insertError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pageevents", 303)
			return 

		}
		defer insertStatement.Close()

		insertStatementExec, insertStatementExecError := insertStatement.Exec(eventType, name, date_start, date_end, description, price, capacity, id_work_address)

		if insertStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pageevents", 303)
			return

		}

		id_event, _ := insertStatementExec.LastInsertId()

		insertStatement2, insertError2 := database.Prepare("INSERT INTO CONDUCT(ID_SERVICE_PROVIDER, ID_EVENT) VALUES(?, ?)")

		if insertError2 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pageevents", 303)
			return 

		}
		defer insertStatement2.Close()

		_, insertStatementExecError2 := insertStatement2.Exec(1, id_event)

		if insertStatementExecError2 != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?error=add_error#pageevents", 303)
			return

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php?notif=add_success#pageevents", 303)

	}

}