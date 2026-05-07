package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ServiceInfo2 struct {
	IsOnline bool `json:"is_online"`
	IsAtConsumerHome bool `json:"is_at_consumer_home"`
}

func ShowServiceInfo(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		serviceType := r.FormValue("service")

		var info ServiceInfo2

		row := database.QueryRow("SELECT is_online, is_at_consumer_home FROM SERVICE WHERE type = ?", serviceType)

		err := row.Scan(&info.IsOnline, &info.IsAtConsumerHome)

		if err != nil {

			json.NewEncoder(w).Encode(ServiceInfo2{})
			return
			
		}

		json.NewEncoder(w).Encode(info)

	}

}