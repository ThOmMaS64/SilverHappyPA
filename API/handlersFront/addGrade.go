package handlersFront

import (
	"database/sql"
	"net/http"
)

func AddGrade(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")

		idIntervention := r.FormValue("id_intervention")

		grade := r.FormValue("grade")

		description := r.FormValue("description")

		var idConsumer int

		row := database.QueryRow("SELECT ID_CONSUMER FROM CONSUMER WHERE ID_USER = ?", id)

		err := row.Scan(&idConsumer)

		if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/opinion.php?error=system", 303)
			return

		}

		insertStatement, errInsert := database.Prepare("INSERT INTO GRADE (grade, description, date, ID_INTERVENTION, ID_CONSUMER) VALUES (?, ?, CURDATE(), ?, ?)")

		if errInsert != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/opinion.php?error=system", http.StatusSeeOther)
			return

		}

		_, errInsertExec := insertStatement.Exec(grade, description, idIntervention, idConsumer)

		if errInsertExec != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/opinion.php?error=system", http.StatusSeeOther)
			return

		}



		http.Redirect(w, r, "http://localhost/ProjetAnnuel/opinion.php?notif=grade_sent", http.StatusSeeOther)

	}

}