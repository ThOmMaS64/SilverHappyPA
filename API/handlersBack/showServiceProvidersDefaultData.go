package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

type ServiceAndProvider struct{
	Email string `json:"email"`
	ID_SERVICE_PROVIDER int `json:"ID_SERVICE_PROVIDER"`
	Type string `json:"type"`
	Username string `json:"username"`
}

func ShowServiceProvidersDefaultData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		offsetString := r.FormValue("offset")
		offset := 0

		if offsetString != ""{

			offset, _ = strconv.Atoi(offsetString)

		}

		rowsServiceAndProviders, err := database.Query("SELECT SERVICE.type, SERVICE_PROVIDER.ID_SERVICE_PROVIDER, USER_.Email, USER_.username FROM SERVICE INNER JOIN OFFER ON SERVICE.ID_SERVICE = OFFER.ID_SERVICE INNER JOIN SERVICE_PROVIDER ON OFFER.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER INNER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER LIMIT 10 OFFSET ?", offset)
		if err != nil {
			http.Error(w, "Erreur lors de la récupération des données depuis la base de données", 500)
			return 
		}	
		defer rowsServiceAndProviders.Close()

		var serviceAndProviders []ServiceAndProvider
		
		for rowsServiceAndProviders.Next(){

			var serviceAndProvider ServiceAndProvider

			err := rowsServiceAndProviders.Scan(&serviceAndProvider.Type, &serviceAndProvider.ID_SERVICE_PROVIDER,  &serviceAndProvider.Email, &serviceAndProvider.Username)	
			if err != nil {
				continue
			}

			serviceAndProviders = append(serviceAndProviders, serviceAndProvider)
		}
		
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(serviceAndProviders)

	}

}