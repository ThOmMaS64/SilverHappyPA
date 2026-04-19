package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

func ShowPersonalizedEventsPage(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		research := r.FormValue("research")
		filter := r.FormValue("filter")
		sort := r.FormValue("sort")

		response := ResponseEvent{
			Events: []Event{},
		}

		id := r.FormValue("id")

		var args []any
		 
		basicQuery := "SELECT EVENT.ID_EVENT, EVENT.type, EVENT.name, EVENT.date_start, EVENT.date_end, EVENT.description, EVENT.price, EVENT.capacity, EVENT.nb_inscription, COALESCE(WORK_ADDRESS.city, ''), COALESCE(WORK_ADDRESS.street, ''), COALESCE(WORK_ADDRESS.nb_street, 0), COALESCE(WORK_ADDRESS.postal_code, ''), (USER_INTERACTION_EVENT.ID_USER IS NOT NULL) AS is_saved, (PARTICIPATE.ID_EVENT IS NOT NULL) AS is_subscribe FROM EVENT LEFT JOIN USER_INTERACTION_EVENT ON EVENT.ID_EVENT = USER_INTERACTION_EVENT.ID_EVENT AND USER_INTERACTION_EVENT.ID_USER = ? LEFT JOIN CONSUMER ON CONSUMER.ID_USER = ? LEFT JOIN PARTICIPATE ON PARTICIPATE.ID_CONSUMER = CONSUMER.ID_CONSUMER AND PARTICIPATE.ID_EVENT = EVENT.ID_EVENT LEFT JOIN WORK_ADDRESS ON EVENT.ID_WORK_ADDRESS = WORK_ADDRESS.ID_WORK_ADDRESS WHERE 1=1"
		args = append(args, id, id)

		if research != ""{

			basicQuery += " AND (EVENT.type LIKE CONCAT('%', ?, '%') OR EVENT.name LIKE CONCAT('%', ?, '%') OR EVENT.description LIKE CONCAT('%', ?, '%'))"
			args = append(args, research, research, research)

		}

		if filter != ""{

			basicQuery += " AND EVENT.type = ?"
			args = append(args, filter)

		}

		if sort == "1"{

			basicQuery += " ORDER BY EVENT.date_start ASC"

		}else if sort == "2"{

			basicQuery += " ORDER BY EVENT.date_start DESC"

		}else if sort == "3"{

			basicQuery += " ORDER BY EVENT.price ASC"

		}else if sort == "4"{

			basicQuery += " ORDER BY EVENT.price DESC"

		}else if sort =="5"{

			rowSelectTypes, errSelecTypes := database.Query("SELECT EVENT.type FROM EVENT JOIN USER_INTERACTION_EVENT ON EVENT.ID_EVENT = USER_INTERACTION_EVENT.ID_EVENT WHERE USER_INTERACTION_EVENT.ID_USER = ?", id)
	
			if errSelecTypes != nil{

				w.WriteHeader(500)
				response.Error = "Erreur lors de la récupération des évenements depuis la base de donnée."
				json.NewEncoder(w).Encode(response)
				return 

			}

			defer rowSelectTypes.Close()

			var types []string

			for rowSelectTypes.Next(){

				var eventType string

				err := rowSelectTypes.Scan(&eventType)

				if err == nil{

					types = append(types, eventType)

				}
			}

			if len(types) > 0 {

				basicQuery += " ORDER BY ("
				
				for i:=0; i<len(types); i++{

					if i != 0 {
						
						basicQuery += " OR "

					}

					basicQuery += "EVENT.type = ?"
					args = append(args, types[i])

				}

				basicQuery += ") DESC"

			}

		}

		rowSelectEvents, errSelectEvents := database.Query(basicQuery, args...)
	
		if errSelectEvents != nil{

			response.Error = "Erreur lors de la récupération des évenements depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectEvents.Close()

		for rowSelectEvents.Next(){

			var event Event

			err := rowSelectEvents.Scan(&event.IdEvent, &event.Type, &event.Name, &event.DateStart, &event.DateEnd, &event.Description, &event.Price, &event.Capacity, &event.NbInscription, &event.City, &event.Street, &event.NbStreet, &event.PostalCode, &event.IsSaved, &event.IsSubscribe)

			if err == nil{

				response.Events = append(response.Events, event)

			}
		}

		json.NewEncoder(w).Encode(response)

	}

}