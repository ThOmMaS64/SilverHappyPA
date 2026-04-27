package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type searchedConsumer struct {

	Name string `json:"name"`
	Surname string `json:"surname"`
	Username string `json:"username"`
	Description string `json:"description"`
	KeyWord1 string `json:"key_word1"`
	KeyWord2 string `json:"key_word2"`
	KeyWord3 string `json:"key_word3"`
	IdConsumer int `json:"id_consumer"`

}

type responseSearchedConsumer struct {

	Consumers []searchedConsumer `json:"consumer"`
	Error string `json:"error"`

}

func ShowSearchedConsumers(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		research := r.FormValue("research")

		response := responseSearchedConsumer{
			Consumers: []searchedConsumer{},
		}

		var args []any
		 
		basicQuery := "SELECT USER_.name, USER_.surname, USER_.username, USER_.description, USER_.keyWord1, USER_.keyWord2, USER_.keyWord3, CONSUMER.ID_CONSUMER FROM USER_ INNER JOIN CONSUMER ON USER_.ID_USER = CONSUMER.ID_USER WHERE USER_.status IN (1, 2, 5, 6)"

		if research != ""{

			basicQuery += " AND (USER_.name LIKE CONCAT('%', ?, '%') OR USER_.surname LIKE CONCAT('%', ?, '%') OR USER_.username LIKE CONCAT('%', ?, '%') OR USER_.description LIKE CONCAT('%', ?, '%') OR USER_.keyWord1 LIKE CONCAT('%', ?, '%') OR USER_.keyWord2 LIKE CONCAT('%', ?, '%') OR USER_.keyWord3 LIKE CONCAT('%', ?, '%'))"
			args = append(args, research, research, research, research, research, research, research)

		}

		rowSelectAdvices, errSelectAdvices := database.Query(basicQuery, args...)
	
		if errSelectAdvices != nil{

			response.Error = "Erreur lors de la récupération des adhérants depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectAdvices.Close()

		for rowSelectAdvices.Next(){

			var consumer searchedConsumer

			err := rowSelectAdvices.Scan(&consumer.Name, &consumer.Surname, &consumer.Username, &consumer.Description, &consumer.KeyWord1, &consumer.KeyWord2, &consumer.KeyWord3, &consumer.IdConsumer)

			if err == nil{

				response.Consumers = append(response.Consumers, consumer)

			}
		}

		json.NewEncoder(w).Encode(response)

	}

}