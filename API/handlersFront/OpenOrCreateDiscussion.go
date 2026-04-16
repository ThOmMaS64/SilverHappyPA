package handlersFront

import (
	"database/sql"
	"fmt"
	"net/http"
)

func OpenOrCreateDiscussion(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		idService := r.FormValue("id_service")

		var idServiceProvider int
		var idUserServiceProvider int

		row := database.QueryRow("SELECT SERVICE_PROVIDER.ID_SERVICE_PROVIDER, SERVICE_PROVIDER.ID_USER FROM OFFER INNER JOIN SERVICE_PROVIDER ON SERVICE_PROVIDER.ID_SERVICE_PROVIDER = OFFER.ID_SERVICE_PROVIDER WHERE OFFER.ID_SERVICE = ?", idService)

		err := row.Scan(&idServiceProvider, &idUserServiceProvider)

		if err != nil {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?error=system2", 303)
			return
		}

		var idDiscussion int

		rowDiscussion := database.QueryRow("SELECT ID_DISCUSSION FROM DISCUSSION WHERE ID_SERVICE = ? AND ((user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?))", idService, id, idUserServiceProvider, idUserServiceProvider, id)

		err = rowDiscussion.Scan(&idDiscussion)

		if err == sql.ErrNoRows {
			res, insertErr := database.Exec("INSERT INTO DISCUSSION (user1_id, user2_id, ID_SERVICE) VALUES (?, ?, ?)", id, idUserServiceProvider, idService)

			if insertErr != nil {
				http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?error=system3", 303)
				return
			}

			newID, _ := res.LastInsertId()
			idDiscussion = int(newID)

		} else if err != nil {
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/services.php?error=system4", 303)
			return
		}

		url := fmt.Sprintf("http://localhost/ProjetAnnuel/messaging.php?id_discussion=%d&id_service=%s&info=from_services", idDiscussion, idService)
		http.Redirect(w, r, url, 303)
		 
	}

}