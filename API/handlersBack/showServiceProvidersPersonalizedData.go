package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

func ShowServiceProvidersPersonalizedData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		filter := r.FormValue("filter")
		
		offsetString := r.FormValue("offset")
		offset := 0

		if offsetString != ""{

			offset, _ = strconv.Atoi(offsetString)

		}

		var args []any
		 
		basicQuery := "SELECT SERVICE.type, SERVICE_PROVIDER.ID_SERVICE_PROVIDER, USER_.Email, USER_.username FROM SERVICE INNER JOIN OFFER ON SERVICE.ID_SERVICE = OFFER.ID_SERVICE INNER JOIN SERVICE_PROVIDER ON OFFER.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER WHERE 1=1"

		if filter != ""{

			basicQuery += " AND (SERVICE.type = ?)"
			args = append(args, filter)

		}

		basicQuery += " LIMIT 10 OFFSET ?"
		args = append(args, offset)

		rowSelectUser, errSelectUser := database.Query(basicQuery, args...)
	
		if errSelectUser != nil{

			http.Error(w, "Erreur lors de la récupération des données depuis la base de donnée.", 500)
			return 

		}
		defer rowSelectUser.Close()

		var serviceAndProviders []ServiceAndProvider

		for rowSelectUser.Next(){

			var serviceAndProvider ServiceAndProvider

			err := rowSelectUser.Scan(&serviceAndProvider.Type, &serviceAndProvider.ID_SERVICE_PROVIDER,  &serviceAndProvider.Email, &serviceAndProvider.Username)

			if err != nil{

				http.Error(w, "Erreur lors de la récupération des données depuis la base de données", 500)
				return 

			}

			serviceAndProviders = append(serviceAndProviders, serviceAndProvider)

		}

		json.NewEncoder(w).Encode(serviceAndProviders)

	}

}