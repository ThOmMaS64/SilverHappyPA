package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

func ShowUsersPersonalizedData(database *sql.DB) http.HandlerFunc {

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

		users := []User{}

		var args []any
		 
		basicQuery := "SELECT USER_.ID_USER, COALESCE(USER_.username, ''), COALESCE(USER_.name, ''), COALESCE(USER_.surname, ''), COALESCE(USER_.description, ''), COALESCE(USER_.keyWord1, ''), COALESCE(USER_.keyWord2, ''), COALESCE(USER_.keyWord3, ''), COALESCE(USER_.email, ''), COALESCE(USER_.city, ''), COALESCE(USER_.street, ''), COALESCE(USER_.nb_street, ''), COALESCE(USER_.postal_code, ''), COALESCE(USER_.status, 0), COALESCE(USER_.connected, 0), COALESCE(USER_.last_connection, '1900-01-01 00:00:00'), COALESCE(USER_.date_inscription, '1900-01-01'), COALESCE(USER_.banned, 0), COALESCE(CONSUMER.birth_date, '1900-01-01') FROM USER_ LEFT JOIN CONSUMER ON USER_.ID_USER = CONSUMER.ID_USER WHERE 1=1"

		if research != ""{

			basicQuery += " AND (USER_.username LIKE CONCAT('%', ?, '%') OR USER_.name LIKE CONCAT('%', ?, '%') OR USER_.surname LIKE CONCAT('%', ?, '%') OR USER_.email LIKE CONCAT('%', ?, '%') OR USER_.city LIKE CONCAT('%', ?, '%') OR USER_.postal_code LIKE CONCAT('%', ?, '%'))"
			args = append(args, research, research, research, research, research, research)

		}

		if filter != ""{

			if filter == "1"{

				basicQuery += " AND (USER_.status = -1 OR USER_.status = 1 OR USER_.status = 2 OR USER_.status = 5 OR USER_.status = 6)"

			}else if filter == "2"{

				basicQuery += " AND (USER_.status = -2 OR USER_.status = 3 OR USER_.status = 4)"

			}else if filter == "3"{

				basicQuery += " AND (USER_.connected = 1)"

			}else if filter == "4"{

				basicQuery += " AND (USER_.connected = 0)"

			}else if filter == "5"{

				basicQuery += " AND (USER_.status = 3)"

			}

		}

		if sort != ""{

			if sort == "1"{

				basicQuery += " ORDER BY USER_.date_inscription ASC"

			}else if sort == "2"{

				basicQuery += " ORDER BY USER_.date_inscription DESC"

			}

		}

		basicQuery += " LIMIT 10 OFFSET ?"
		args = append(args, offset)

		rowSelectUser, errSelectUser := database.Query(basicQuery, args...)
	
		if errSelectUser != nil{

			http.Error(w, "Erreur lors de la récupération des Conseils depuis la base de donnée.", 500)
			return 

		}
		defer rowSelectUser.Close()

		for rowSelectUser.Next(){

			var user User

			err := rowSelectUser.Scan(&user.ID_USER, &user.Username, &user.Name, &user.Surname, &user.Description, &user.KeyWord1, &user.KeyWord2, &user.KeyWord3, &user.Email, &user.City, &user.Street, &user.Nb_street, &user.Postal_code, &user.Status, &user.Connected, &user.LastConnection, &user.DateInscription, &user.Banned, &user.BirthDate)

			if err == nil{

				users = append(users, user)

			}
		}

		json.NewEncoder(w).Encode(users)

	}

}