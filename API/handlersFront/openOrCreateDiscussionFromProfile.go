package handlersFront

import (
	"database/sql"
	"fmt"
	"net/http"
)

func OpenOrCreateDiscussionFromProfile(database *sql.DB) http.HandlerFunc {
	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		visitedUserId := r.FormValue("visited_user_id")

		var idDiscussion int

		rowDiscussion := database.QueryRow("SELECT ID_DISCUSSION FROM DISCUSSION WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?) LIMIT 1", id, visitedUserId, visitedUserId, id)
		err := rowDiscussion.Scan(&idDiscussion)

		if err == sql.ErrNoRows {

			res, _ := database.Exec("INSERT INTO DISCUSSION (user1_id, user2_id, ID_SERVICE) VALUES (?, ?, NULL)", id, visitedUserId)
			newID, _ := res.LastInsertId()
			idDiscussion = int(newID)

		}

		url := fmt.Sprintf("http://localhost/ProjetAnnuel/messaging.php?id_discussion=%d", idDiscussion)
		http.Redirect(w, r, url, 303)

	}
}