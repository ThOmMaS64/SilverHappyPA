package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

func ShowPersonalizedStorePage(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		research := r.FormValue("research")
		filter := r.FormValue("filter")
		sort := r.FormValue("sort")

		response := ResponseProduct{
			Products: []Product{},
		}

		id := r.FormValue("id")

		var args []any
		 
		basicQuery := `SELECT PRODUCT.ID_PRODUCT, PRODUCT.name, PRODUCT.description, PRODUCT.type, PRODUCT.price, PRODUCT.stock, PRODUCT.date_added, COALESCE(PRODUCT_IMAGE.image_path, ''), 
				COALESCE(Cart.quantity, 0) FROM PRODUCT LEFT JOIN PRODUCT_IMAGE ON PRODUCT.ID_PRODUCT = PRODUCT_IMAGE.ID_PRODUCT
			LEFT JOIN (
				SELECT ORDER_LINE.ID_PRODUCT, SUM(ORDER_LINE.quantity) as quantity
				FROM ORDER_LINE
				JOIN SHOP_ORDER ON ORDER_LINE.ID_SHOP_ORDER = SHOP_ORDER.ID_SHOP_ORDER
				JOIN CONSUMER ON SHOP_ORDER.ID_CONSUMER = CONSUMER.ID_CONSUMER
				WHERE CONSUMER.ID_USER = ? AND SHOP_ORDER.status = 0 
				GROUP BY ORDER_LINE.ID_PRODUCT
			) AS Cart ON PRODUCT.ID_PRODUCT = Cart.ID_PRODUCT
			WHERE 1=1`
		args = append(args, id)

		if research != ""{

			basicQuery += " AND (PRODUCT.name LIKE CONCAT('%', ?, '%') OR PRODUCT.type LIKE CONCAT('%', ?, '%') OR PRODUCT.description LIKE CONCAT('%', ?, '%'))"
			args = append(args, research, research, research)

		}

		if filter != ""{

			basicQuery += " AND PRODUCT.type = ?"
			args = append(args, filter)

		}

		if sort == "1"{

			basicQuery += " ORDER BY PRODUCT.price ASC"

		}else if sort == "2"{

			basicQuery += " ORDER BY PRODUCT.price DESC"

		}else if sort =="3"{

			basicQuery += " ORDER BY PRODUCT.date_added DESC"

		}else if sort =="4"{

			basicQuery += " ORDER BY PRODUCT.date_added ASC"

		}

		rowSelectProducts, errSelectProducts := database.Query(basicQuery, args...)
	
		if errSelectProducts != nil{

			response.Error = "Erreur lors de la récupération des produits depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectProducts.Close()

		for rowSelectProducts.Next(){

			var product Product

			err := rowSelectProducts.Scan(&product.IdProduct, &product.Name, &product.Description, &product.Type, &product.Price, &product.Stock, &product.DateAdded, &product.ImagePath, &product.QuantityInCart)

			if err == nil{

				response.Products = append(response.Products, product)

			}
		}

		json.NewEncoder(w).Encode(response)

	}

}