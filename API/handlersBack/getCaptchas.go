package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type Captcha struct {
	ID       int    `json:"id"`
	Question string `json:"question"`
	Answer   string `json:"answer"`
}

type ResponseCaptchas struct {
	Captchas []Captcha `json:"captchas"`
	Error    string    `json:"error"`
}

func GetCaptchas(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseCaptchas{
			Captchas: []Captcha{},
		}

		rows, errQuery := database.Query("SELECT ID_CAPTCHA, question, answer FROM CAPTCHA ORDER BY ID_CAPTCHA ASC")

		if errQuery != nil {

			response.Error = "Erreur lors de la récupération des captchas."
			json.NewEncoder(w).Encode(response)
			return

		}
		defer rows.Close()

		for rows.Next() {

			var captcha Captcha

			err := rows.Scan(&captcha.ID, &captcha.Question, &captcha.Answer)

			if err == nil {

				response.Captchas = append(response.Captchas, captcha)

			}

		}

		json.NewEncoder(w).Encode(response)

	}

}