package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

type Event struct{
	ID_EVENT int `json:"ID_EVENT"`
	Name string `json:"name"`
	Type string `json:"type"`
	DateStart string `json:"date_start"`
	DateEnd string `json:"date_end"`
	Description string `json:"description"`
	Price float64 `json:"price"`
	Capacity int `json:"capacity"`
	NbInscription int `json:"nb_inscription"`
	City string `json:"city"`
	Street string `json:"street"`
	NbStreet int `json:"nb_street"`
	PostalCode string `json:"postal_code"`
	ID_WORK_ADDRESS int `json:"ID_WORK_ADDRESS"`
}

type ResponseEvents struct {

	Types []string `json:"types"`
	Events []Event `json:"events"`

}

func ShowEventsDefaultData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		offsetString := r.FormValue("offset")
		offset := 0

		if offsetString != ""{

			offset, _ = strconv.Atoi(offsetString)

		}

		response := ResponseEvents{
			Types: []string{},
			Events: []Event{},
		}

		rowSelectType, errSelectType := database.Query("SELECT DISTINCT type FROM EVENT ORDER BY type ASC")
	
		if errSelectType != nil {

			http.Error(w, "Erreur lors de la récupération des Types depuis la base de donnée.", 500)
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

		rowsEvents, err := database.Query("SELECT EVENT.ID_EVENT, EVENT.type, EVENT.name, EVENT.date_start, EVENT.date_end, EVENT.description, EVENT.price, EVENT.capacity, EVENT.nb_inscription, EVENT.ID_WORK_ADDRESS, WORK_ADDRESS.city, WORK_ADDRESS.street, WORK_ADDRESS.nb_street, WORK_ADDRESS.postal_code FROM EVENT LEFT JOIN WORK_ADDRESS on EVENT.ID_WORK_ADDRESS = WORK_ADDRESS.ID_WORK_ADDRESS LEFT JOIN CONDUCT ON EVENT.ID_EVENT = CONDUCT.ID_EVENT LEFT JOIN SERVICE_PROVIDER ON CONDUCT.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER LEFT JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER LIMIT 10 OFFSET ?", offset)
		if err != nil {
			http.Error(w, err.Error(), 500)
			return 
		}	
		defer rowsEvents.Close()

		for rowsEvents.Next(){

			var event Event

			var dateStart, dateEnd, description, city, street, postalCode sql.NullString
			var nbStreet, capacity, nb_inscription sql.NullInt64
			var price sql.NullFloat64

			err := rowsEvents.Scan(&event.ID_EVENT, &event.Type, &event.Name, &dateStart, &dateEnd, &description, &price, &capacity, &nb_inscription, &event.ID_WORK_ADDRESS, &city, &street, &nbStreet, &postalCode)	
			if err != nil {
				continue
			}

			event.DateStart = dateStart.String
			event.DateEnd = dateEnd.String
			event.Description = description.String
			event.Price = float64(price.Float64)
			event.Capacity =  int(capacity.Int64)
			event.NbInscription =  int(nb_inscription.Int64)
			event.City = city.String
			event.Street = street.String
			event.NbStreet = int(nbStreet.Int64)
			event.PostalCode = postalCode.String

			response.Events = append(response.Events, event)
		}
		
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(response)

	}

}