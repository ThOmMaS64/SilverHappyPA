package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type CartLine struct {
	IdProduct int `json:"id_product"`
	Name string `json:"name"`
	Quantity int `json:"quantity"`
	Price float64 `json:"price"`
}

type CartResponse struct {
	Products []CartLine `json:"products"`
	TotalPrice float64 `json:"total_price"`
	Error string `json:"error"`
}

func ShowCart(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")

		var response CartResponse
		response.Products = []CartLine{}

		var idConsumer int

		row := database.QueryRow("SELECT ID_CONSUMER FROM CONSUMER WHERE ID_USER = ?", id)
	
		err := row.Scan(&idConsumer)

		if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=system", 303)
			return

		}

		var idShopOrder int
		var totalPrice float64

		row = database.QueryRow("SELECT ID_SHOP_ORDER, total_price FROM SHOP_ORDER WHERE ID_CONSUMER = ? AND status = 0", idConsumer)
	
		err = row.Scan(&idShopOrder, &totalPrice)

		if err == sql.ErrNoRows {
			response.TotalPrice = 0
			json.NewEncoder(w).Encode(response)
			return
		}else if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=system", 303)
			return

		}

		rowSelect, errSelect := database.Query("SELECT PRODUCT.ID_PRODUCT, PRODUCT.name, ORDER_LINE.quantity, ORDER_LINE.price FROM ORDER_LINE INNER JOIN PRODUCT ON ORDER_LINE.ID_PRODUCT = PRODUCT.ID_PRODUCT WHERE ID_SHOP_ORDER = ?", idShopOrder)
	
		if errSelect != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=system", 303)
			return

		}

		defer rowSelect.Close()

		for rowSelect.Next() {
			var item CartLine
			errScan := rowSelect.Scan(&item.IdProduct, &item.Name, &item.Quantity, &item.Price)

			if errScan == nil {
				response.Products = append(response.Products, item)
			}
		}

		json.NewEncoder(w).Encode(response)

	}
	
}