package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"time"
)

func SaveServiceSlots(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		idServiceProvider := r.FormValue("id_service_provider")

		startStr := r.FormValue("date") + "T" + r.FormValue("start_time")
		endStr := r.FormValue("date") + "T" + r.FormValue("end_time")

		const layout = "2006-01-02T15:04"

		startTime, errStart := time.Parse(layout, startStr)

		if errStart != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=add_error", 303)
			return

		}

		endTime, errEnd := time.Parse(layout, endStr)

		if errEnd != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=add_error", 303)
			return

		}

		idService := r.FormValue("id_service")
		isRecurring := r.FormValue("is_recurring") == "on"

		nbWeeks := 1

		if isRecurring {

			nbWeeks = 52

		}

		for i := 0; i < nbWeeks; i++ {

			start := startTime.AddDate(0, 0, i*7).Format("2006-01-02 15:04:05")
			end := endTime.AddDate(0, 0, i*7).Format("2006-01-02 15:04:05")

			var count int

			rowCheck := database.QueryRow("SELECT COUNT(*) FROM SERVICE_SLOT WHERE ID_SERVICE_PROVIDER = ? AND start_time < ? AND end_time > ?", idServiceProvider, end, start)

			errCheck := rowCheck.Scan(&count)

			if errCheck != nil {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system1", 303)
				return

			}

			if count > 0 {
				continue
			}

			_, errInsert := database.Exec("INSERT INTO SERVICE_SLOT (ID_SERVICE_PROVIDER, ID_SERVICE, start_time, end_time, is_booked) VALUES (?, ?, ?, ?, 0)", idServiceProvider, idService, start, end)

			if errInsert != nil {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system2", 303)
				return

			}

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?notif=slot_added", 303)

	}

}

type SlotEvent struct {
	ID       int    `json:"id"`
	Start    string `json:"start"`
	End      string `json:"end"`
	IsBooked bool   `json:"is_booked"`
}

type ResponseGetSlots struct {
	Slots []SlotEvent `json:"slots"`
	Error string      `json:"error"`
}

func GetServiceProviderSlots(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseGetSlots{
			Slots: []SlotEvent{},
		}

		idServiceProvider := r.FormValue("id_service_provider")

		rows, errQuery := database.Query("SELECT ID_SERVICE_SLOT, start_time, end_time, is_booked FROM SERVICE_SLOT WHERE ID_SERVICE_PROVIDER = ? ORDER BY start_time ASC", idServiceProvider)

		if errQuery != nil {

			response.Error = "Erreur lors de la récupération des créneaux."
			json.NewEncoder(w).Encode(response)
			return

		}

		defer rows.Close()

		for rows.Next() {

			var slot SlotEvent
			var startRaw, endRaw []byte

			err := rows.Scan(&slot.ID, &startRaw, &endRaw, &slot.IsBooked)

			if err == nil {

				slot.Start = string(startRaw)
				slot.End = string(endRaw)
				response.Slots = append(response.Slots, slot)

			}

		}

		json.NewEncoder(w).Encode(response)

	}

}