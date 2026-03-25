package handlersFront

import (
	"database/sql"
	"net/http"
)

func SaveUnsaveEvent(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		eventId := r.FormValue("id_event")

		var existingRow int

		row := database.QueryRow("SELECT ID_USER FROM USER_INTERACTION_EVENT WHERE ID_USER = ? AND ID_EVENT = ?", id, eventId)
	
		err := row.Scan(&existingRow)

		if err == sql.ErrNoRows {	

			insertStatement, insertError := database.Prepare("INSERT INTO USER_INTERACTION_EVENT(ID_USER, ID_EVENT) VALUES(?, ?)")

			if insertError != nil {

				http.Error(w, "Erreur système 1", 500)
				return 

			}
			defer insertStatement.Close()

			_, insertExecError := insertStatement.Exec(id, eventId)

			if insertExecError != nil {

				http.Error(w, "Erreur système 2", 500)
				return 

			}

			w.WriteHeader(200)
			w.Write([]byte("save_success"))

		}else if err == nil{

			deleteStatement, deleteError := database.Prepare("DELETE FROM USER_INTERACTION_EVENT WHERE ID_USER = ? AND ID_EVENT = ?")

			if deleteError != nil {

				http.Error(w, "Erreur système 3", 500)
				return 

			}
			defer deleteStatement.Close()

			_, deleteErrorExecError := deleteStatement.Exec(id, eventId)

			if deleteErrorExecError != nil {

				http.Error(w, "Erreur système 4", 500)
				return 

			}

			w.WriteHeader(200)		
			w.Write([]byte("unsave_success"))	

		}else{

			http.Error(w, "Erreur système 5", 500)
			return 

		}
		 
	}

}