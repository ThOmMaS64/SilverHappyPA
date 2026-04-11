package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

func ShowRegisteredEvent(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseEvent{
			Types: []string{},
			Events: []Event{},
		}

		id := r.FormValue("id")

		rowSelectEvents, errSelectEvents := database.Query("SELECT EVENT.ID_EVENT, EVENT.name, EVENT.description, EVENT.date_start, EVENT.date_end, COALESCE(WORK_ADDRESS.city , ''), COALESCE(WORK_ADDRESS.street, ''), COALESCE(WORK_ADDRESS.nb_street, 0), COALESCE(WORK_ADDRESS.postal_code, '') FROM EVENT JOIN PARTICIPATE ON EVENT.ID_EVENT = PARTICIPATE.ID_EVENT JOIN CONSUMER ON PARTICIPATE.ID_CONSUMER = CONSUMER.ID_CONSUMER JOIN WORK_ADDRESS ON EVENT.ID_WORK_ADDRESS = WORK_ADDRESS.ID_WORK_ADDRESS WHERE CONSUMER.ID_USER = ?", id)
	
		if errSelectEvents != nil{

			w.WriteHeader(500)
			response.Error = "Erreur lors de la récupération des Événements depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectEvents.Close()

		for rowSelectEvents.Next(){

			var event Event

			err := rowSelectEvents.Scan(&event.IdEvent, &event.Name, &event.Description, &event.DateStart, &event.DateEnd, &event.City, &event.Street, &event.NbStreet, &event.PostalCode)

			if err == nil{

				response.Events = append(response.Events, event)

			}
		}

		json.NewEncoder(w).Encode(response)
		 
	}

}