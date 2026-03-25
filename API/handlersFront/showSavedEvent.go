package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

func ShowSavedEvent(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseEvent{
			Types: []string{},
			Events: []Event{},
		}

		id := r.FormValue("id")

		rowSelectEvents, errSelectEvents := database.Query("SELECT EVENT.ID_EVENT, EVENT.name, EVENT.description, EVENT.date_start, EVENT.date_end FROM EVENT JOIN USER_INTERACTION_EVENT ON EVENT.ID_EVENT = USER_INTERACTION_EVENT.ID_EVENT WHERE USER_INTERACTION_EVENT.ID_USER = ?", id)
	
		if errSelectEvents != nil{

			w.WriteHeader(500)
			response.Error = "Erreur lors de la récupération des Événements depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectEvents.Close()

		for rowSelectEvents.Next(){

			var event Event

			err := rowSelectEvents.Scan(&event.IdEvent, &event.Name, &event.Description, &event.DateStart, &event.DateEnd)

			if err == nil{

				response.Events = append(response.Events, event)

			}
		}

		json.NewEncoder(w).Encode(response)
		 
	}

}