package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

func AdvertisementProfile(database *sql.DB) http.HandlerFunc {
	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		var advertisement Advertisement

		row := database.QueryRow("SELECT ADVERTISEMENT.ID_SERVICE_PROVIDER, ADVERTISEMENT.title, ADVERTISEMENT.description, USER_.name, USER_.surname, USER_.profilePicture FROM ADVERTISEMENT JOIN SERVICE_PROVIDER ON ADVERTISEMENT.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER WHERE ADVERTISEMENT.type IN (2, 3) AND ADVERTISEMENT.date_paiement >= DATE_SUB(NOW(), INTERVAL 3 MONTH) ORDER BY RAND() LIMIT 1")

		err := row.Scan(&advertisement.IdServiceProvider, &advertisement.Title, &advertisement.Description, &advertisement.Name, &advertisement.Surname, &advertisement.ProfilePicture)
		
		if err == sql.ErrNoRows{

			advertisement.Error = "Aucune pulbicité disponnible."
			json.NewEncoder(w).Encode(advertisement)
			return

		}else if err != nil {

			advertisement.Error = "Erreur lors de la récupération d'une publicité depuis la base de donnée."
			json.NewEncoder(w).Encode(advertisement)
			return

		}

		json.NewEncoder(w).Encode(advertisement)

	}
}