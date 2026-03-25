package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type Event struct {

	IdEvent int `json:"id_event"`
	Type string `json:"type"`
	Name string `json:"name"`
	DateStart string `json:"date_start"`
	DateEnd string `json:"date_end"`
	Description string `json:"description"`
	Price float64 `json:"price"`
	Capacity int `json:"capacity"`
	NbInscription int `json:"nb_inscription"`
	IsSaved bool `json:"is_saved"`
	IsSubscribe bool `json:"is_subscribe"`

}

type ResponseEvent struct {

	Types []string `json:"types"`
	Events []Event `json:"events"`
	Error string `json:"error"`

}

func ShowDefaultEventsPage(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseEvent{
			Types: []string{},
			Events: []Event{},
		}

		id := r.FormValue("id")

		rowSelectType, errSelectType := database.Query("SELECT DISTINCT type FROM EVENT ORDER BY type ASC")
	
		if errSelectType != nil {

			response.Error = "Erreur lors de la récupération des types depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectType.Close()

		for rowSelectType.Next(){

			var eventType string

			err := rowSelectType.Scan(&eventType)

			if err == nil{

				response.Types = append(response.Types, eventType)

			}

		}
		

		rowSelectEvents, errSelectEvents := database.Query("SELECT EVENT.ID_EVENT, EVENT.type, EVENT.name, EVENT.date_start, EVENT.date_end, EVENT.description, EVENT.price, EVENT.capacity, EVENT.nb_inscription, (USER_INTERACTION_EVENT.ID_USER IS NOT NULL) AS is_saved, (PARTICIPATE.ID_EVENT IS NOT NULL) AS is_subscribe FROM EVENT LEFT JOIN USER_INTERACTION_EVENT ON EVENT.ID_EVENT = USER_INTERACTION_EVENT.ID_EVENT AND USER_INTERACTION_EVENT.ID_USER = ? LEFT JOIN CONSUMER ON CONSUMER.ID_USER = ? LEFT JOIN PARTICIPATE ON PARTICIPATE.ID_CONSUMER = CONSUMER.ID_CONSUMER AND PARTICIPATE.ID_EVENT = EVENT.ID_EVENT", id, id)
	
		if errSelectEvents != nil{

			w.WriteHeader(500)
			response.Error = "Erreur lors de la récupération des événements depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectEvents.Close()

		for rowSelectEvents.Next(){

			var event Event

			err := rowSelectEvents.Scan(&event.IdEvent, &event.Type, &event.Name, &event.DateStart, &event.DateEnd, &event.Description, &event.Price, &event.Capacity, &event.NbInscription, &event.IsSaved, &event.IsSubscribe)

			if err == nil{

				response.Events = append(response.Events, event)

			}
		}

		json.NewEncoder(w).Encode(response)
		 
	}

}