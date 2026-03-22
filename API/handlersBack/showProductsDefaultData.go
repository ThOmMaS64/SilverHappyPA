package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

type Product struct{
	ID_PRODUCT int `json:"ID_PRODUCT"`
	Name string `json:"name"`
	Type string `json:"type"`
	Description string `json:"description"`
	Price float64 `json:"price"`
}

type ResponseProducts struct {

	Types []string `json:"types"`
	Products []Product `json:"products"`

}

func ShowProductsDefaultData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		offsetString := r.FormValue("offset")
		offset := 0

		if offsetString != ""{

			offset, _ = strconv.Atoi(offsetString)

		}

		response := ResponseProducts{
			Types: []string{},
			Products: []Product{},
		}

		rowSelectType, errSelectType := database.Query("SELECT DISTINCT type FROM PRODUCT ORDER BY type ASC")
	
		if errSelectType != nil {

			http.Error(w, "Erreur lors de la récupération des Types depuis la base de donnée.", 500)
			return 

		}

		defer rowSelectType.Close()

		for rowSelectType.Next(){

			var productType string

			err := rowSelectType.Scan(&productType)

			if err == nil{

				response.Types = append(response.Types, productType)

			}

		}

		rowsProd, err := database.Query("SELECT ID_PRODUCT, name, type, description, price FROM PRODUCT LIMIT 10 OFFSET ?", offset)
		if err != nil {
			http.Error(w, "Erreur lors de la récupération des données depuis la base de données.", 500)
			return 
		}	
		defer rowsProd.Close()

		for rowsProd.Next(){

			var prod Product

			err := rowsProd.Scan(&prod.ID_PRODUCT, &prod.Name, &prod.Type, &prod.Description, &prod.Price)
			if err != nil {
				continue
			}

			response.Products = append(response.Products, prod)
		}
		
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(response)

	}

}