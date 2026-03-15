package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type Product struct{
	ID_PRODUCT int `json:"ID_PRODUCT"`
	Name string `json:"name"`
	Type string `json:"type"`
	Description string `json:"description"`
	Price float64 `json:"price"`
}

func BackShowShop(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		rowsProd, err := database.Query("SELECT ID_PRODUCT, name, type, description, price FROM product LIMIT 10")
		if err != nil {
			http.Error(w, err.Error(), 500)
			return 
		}	
		defer rowsProd.Close()

		var shop []Product

		for rowsProd.Next(){

			var prod Product

			err := rowsProd.Scan(&prod.ID_PRODUCT, &prod.Name, &prod.Type, &prod.Description, &prod.Price)	
			if err != nil {
				continue
			}

			shop = append(shop, prod)
		}
		
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(shop)

	}

}