package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type Event struct{
	ID_EVENT int `json:"ID_EVENT"`
	Name string `json:"name"`
	Type string `json:"type"`
	Date string `json:"date_"`
	Description string `json:"description"`
}

func BackShowEvents(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		rowsEvents, err := database.Query("SELECT ID_EVENT, name, type, date_, description FROM event LIMIT 10")
		if err != nil {
			http.Error(w, err.Error(), 500)
			return 
		}	
		defer rowsEvents.Close()

		var events []Event
		

		for rowsEvents.Next(){

			var event Event

			err := rowsEvents.Scan(&event.ID_EVENT, &event.Name, &event.Type, &event.Date, &event.Description)	
			if err != nil {
				continue
			}

			events = append(events, event)
		}
		
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(events)

	}

}