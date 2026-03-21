package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

func ShowEventsPersonalizedData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		research := r.FormValue("research")
		filter := r.FormValue("filter")
		sort := r.FormValue("sort")
		
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

			var typeEvent string

			err := rowSelectType.Scan(&typeEvent)

			if err == nil{

				response.Types = append(response.Types, typeEvent)

			}

		}

		var args []any
		 
		basicQuery := "SELECT EVENT.ID_EVENT, EVENT.type, EVENT.name, EVENT.date_start, EVENT.date_end, EVENT.description, EVENT.ID_WORK_ADDRESS, WORK_ADDRESS.city, WORK_ADDRESS.street, WORK_ADDRESS.nb_street, WORK_ADDRESS.postal_code, USER_.username FROM EVENT LEFT JOIN WORK_ADDRESS on EVENT.ID_WORK_ADDRESS = WORK_ADDRESS.ID_WORK_ADDRESS LEFT JOIN CONDUCT ON EVENT.ID_EVENT = CONDUCT.ID_EVENT LEFT JOIN SERVICE_PROVIDER ON CONDUCT.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER LEFT JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER WHERE 1=1"

		if research != ""{

			basicQuery += " AND (EVENT.type LIKE CONCAT('%', ?, '%') OR EVENT.name LIKE CONCAT('%', ?, '%') OR EVENT.description LIKE CONCAT('%', ?, '%') OR USER_.username LIKE CONCAT('%', ?, '%'))"
			args = append(args, research, research, research, research)

		}

		if filter == "perso1"{

			basicQuery += " AND (EVENT.date_end < CURDATE())"

		}else if filter == "perso2"{

			basicQuery += " AND (EVENT.date_start > CURDATE())"

		}else if filter != ""{

			basicQuery += " AND (EVENT.type = ?)"
			args = append(args, filter)

		}

		if sort != ""{

			if sort == "1"{

				basicQuery += " ORDER BY EVENT.date_start ASC"

			}else if sort == "2"{

				basicQuery += " ORDER BY EVENT.date_end DESC"

			}else if sort == "3"{

				basicQuery += " ORDER BY (SELECT COUNT(*) FROM PARTICIPATE WHERE PARTICIPATE.ID_EVENT = EVENT.ID_EVENT) DESC"

			}else if sort == "4"{

				basicQuery += " ORDER BY (SELECT COUNT(*) FROM PARTICIPATE WHERE PARTICIPATE.ID_EVENT = EVENT.ID_EVENT) ASC"

			}

		}

		basicQuery += " LIMIT 10 OFFSET ?"
		args = append(args, offset)

		rowSelectEvent, errSelectEvent := database.Query(basicQuery, args...)
	
		if errSelectEvent != nil{

			http.Error(w, "Erreur lors de la récupération des événements depuis la base de donnée.", 500)
			return 

		}
		defer rowSelectEvent.Close()

		for rowSelectEvent.Next(){

			var dateStart, dateEnd, description, city, street, postalCode sql.NullString
			var nbStreet sql.NullInt64

			var event Event

			err := rowSelectEvent.Scan(&event.ID_EVENT, &event.Type, &event.Name, &dateStart, &dateEnd, &description, &event.ID_WORK_ADDRESS, &city, &street, &nbStreet, &postalCode, &event.Username)

			if err == nil{

				event.DateStart = dateStart.String
				event.DateEnd = dateEnd.String
				event.Description = description.String
				event.City = city.String
				event.Street = street.String
				event.NbStreet = int(nbStreet.Int64)
				event.PostalCode = postalCode.String

				response.Events = append(response.Events, event)

			}
		}

		json.NewEncoder(w).Encode(response)

	}

}