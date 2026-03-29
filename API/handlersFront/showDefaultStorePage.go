package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type Product struct {

	IdProduct int `json:"id_product"`
	Name string `json:"name"`
	Description string `json:"description"`
	Type string `json:"type"`
	Price float64 `json:"price"`
	Stock int `json:"stock"`
	DateAdded string `json:"date_added"`
	ImagePath string `json:"image_path"`
	QuantityInCart int `json:"quantity_in_cart"`

}

type ResponseProduct struct {

	Types []string `json:"types"`
	Products []Product `json:"products"`
	Error string `json:"error"`

}

func ShowDefaultStorePage(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		id := r.FormValue("id")

		response := ResponseProduct{
			Types: []string{},
			Products: []Product{},
		}

		rowSelectType, errSelectType := database.Query("SELECT DISTINCT type FROM PRODUCT ORDER BY type ASC")
	
		if errSelectType != nil {

			response.Error = "Erreur lors de la récupération des types depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectType.Close()

		for rowSelectType.Next(){

			var typeProduct string

			err := rowSelectType.Scan(&typeProduct)

			if err == nil{

				response.Types = append(response.Types, typeProduct)

			}

		}

		query := `SELECT PRODUCT.ID_PRODUCT, PRODUCT.name, PRODUCT.description, PRODUCT.type, PRODUCT.price, PRODUCT.stock, PRODUCT.date_added, COALESCE(PRODUCT_IMAGE.image_path, ''), 
				COALESCE(Cart.quantity, 0) FROM PRODUCT LEFT JOIN PRODUCT_IMAGE ON PRODUCT.ID_PRODUCT = PRODUCT_IMAGE.ID_PRODUCT
			LEFT JOIN (
				SELECT ORDER_LINE.ID_PRODUCT, SUM(ORDER_LINE.quantity) as quantity
				FROM ORDER_LINE
				JOIN SHOP_ORDER ON ORDER_LINE.ID_SHOP_ORDER = SHOP_ORDER.ID_SHOP_ORDER
				JOIN CONSUMER ON SHOP_ORDER.ID_CONSUMER = CONSUMER.ID_CONSUMER
				WHERE CONSUMER.ID_USER = ? AND SHOP_ORDER.status = 0 
				GROUP BY ORDER_LINE.ID_PRODUCT
			) AS Cart ON PRODUCT.ID_PRODUCT = Cart.ID_PRODUCT`	

		rowSelectProducts, errSelectProducts := database.Query(query, id)
	
		if errSelectProducts != nil{

			w.WriteHeader(500)
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