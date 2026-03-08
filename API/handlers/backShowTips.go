package handlers

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type Tip struct{
	ID_ADVICE int `json:"ID_ADVICE"`
	Title string `json:"title"`
	Theme string `json:"theme"`
	Description string `json:"description"`
	Date_publication string `json:"date_publication"`
}

func BackShowTips(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		rowsTips, err := database.Query("SELECT ID_ADVICE, title, theme, description, date_publication FROM advice LIMIT 10")
		if err != nil {
			http.Error(w, err.Error(), 500)
			return 
		}	
		defer rowsTips.Close()

		var tips []Tip
		

		for rowsTips.Next(){

			var tip Tip

			err := rowsTips.Scan(&tip.ID_ADVICE, &tip.Title, &tip.Theme, &tip.Description, &tip.Date_publication)	
			if err != nil {
				continue
			}

			tips = append(tips, tip)
		}
		
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(tips)

	}

}