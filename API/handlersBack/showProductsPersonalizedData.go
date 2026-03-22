package handlersBack

import (
	"database/sql"
	"encoding/json"
	"net/http"
	"strconv"
)

func ShowProductsPersonalizedData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		research := r.FormValue("research")
		filter := r.FormValue("filter")
		sort := r.FormValue("sort")
		
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

				var args []any
		 
		basicQuery := "SELECT ID_PRODUCT, name, type, description, price FROM PRODUCT WHERE 1=1"

		if research != ""{

			basicQuery += " AND (type LIKE CONCAT('%', ?, '%') OR name LIKE CONCAT('%', ?, '%') OR description LIKE CONCAT('%', ?, '%'))"
			args = append(args, research, research, research)

		}

		if filter != ""{

			basicQuery += " AND (type = ?)"
			args = append(args, filter)

		}

		if sort != ""{

			if sort == "1"{

				basicQuery += " ORDER BY price ASC"

			}else if sort == "2"{

				basicQuery += " ORDER BY price DESC"

			}

		}

		basicQuery += " LIMIT 10 OFFSET ?"
		args = append(args, offset)

		rowsProd, err := database.Query(basicQuery, args...)

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