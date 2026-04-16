package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type Discussion struct {
	ID_DISCUSSION int `json:"ID_DISCUSSION"`
	CorrespondentName string `json:"correspondent_name"`
	CorrespondentSurname string `json:"correspondent_surname"`
	ID_SERVICE int `json:"ID_SERVICE"`
}

func ShowDiscussions(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")

		rows, errRows := database.Query("SELECT DISCUSSION.ID_DISCUSSION, USER_.name, USER_.surname, COALESCE(DISCUSSION.ID_SERVICE, 0) FROM DISCUSSION INNER JOIN USER_ ON USER_.ID_USER = CASE WHEN DISCUSSION.user1_id = ? THEN DISCUSSION.user2_id ELSE DISCUSSION.user1_id END WHERE user1_id = ? OR user2_id = ?", id, id, id)
	
		if errRows != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/messaging.php?error=system1", 303)
			return

		}
		defer rows.Close()

		discussions := []Discussion{}

		for rows.Next() {

			var discussion Discussion

			 err := rows.Scan(&discussion.ID_DISCUSSION, &discussion.CorrespondentName, &discussion.CorrespondentSurname, &discussion.ID_SERVICE)

			if err != nil {
				continue
			}
			discussions = append(discussions, discussion)
		}

		json.NewEncoder(w).Encode(discussions)
	}

}