package handlersFront

import (
	"database/sql"
	"net/http"
	"strconv"
)

func UpdateItemData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		idProduct := r.FormValue("id_product")
		quantityStr:= r.FormValue("quantity")

		quantity, _ := strconv.Atoi(quantityStr)

		var idConsumer int

		row := database.QueryRow("SELECT ID_CONSUMER FROM CONSUMER WHERE ID_USER = ?", id)
	
		err := row.Scan(&idConsumer)

		if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=update_error", 303)
			return

		}

		var idShopOrder int

		row = database.QueryRow("SELECT ID_SHOP_ORDER FROM SHOP_ORDER WHERE ID_CONSUMER = ? AND status = 0", idConsumer)
	
		err = row.Scan(&idShopOrder)

		if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=update_error", 303)
			return

		}

		if quantity == 0{

			deleteStatement, deleteError := database.Prepare("DELETE FROM ORDER_LINE WHERE ID_PRODUCT = ? AND ID_SHOP_ORDER = ?")

			if deleteError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=update_error", 303)
				return 

			}
			defer deleteStatement.Close()

			_, deleteStatementExecError := deleteStatement.Exec(idProduct, idShopOrder)

			if deleteStatementExecError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=update_error", 303)
				return

			}

		}else{

			var unitPrice float64

			row = database.QueryRow("SELECT price FROM PRODUCT WHERE ID_PRODUCT = ?", idProduct)
		
			err = row.Scan(&unitPrice)

			if err != nil {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=update_error", 303)
				return

			}

			newLinePrice := unitPrice * float64(quantity)

			updateStatement, updateError := database.Prepare("UPDATE ORDER_LINE SET quantity = ?, price = ? WHERE ID_PRODUCT = ? AND ID_SHOP_ORDER = ?")

			if updateError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=update_error", 303)
				return 

			}
			defer updateStatement.Close()

			_, updateStatementExecError := updateStatement.Exec(quantity, newLinePrice, idProduct, idShopOrder)

			if updateStatementExecError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=update_error", 303)
				return

			}

		}

		database.Exec("UPDATE SHOP_ORDER SET total_price = (SELECT COALESCE(SUM(price),0) FROM ORDER_LINE WHERE ID_SHOP_ORDER = ?) WHERE ID_SHOP_ORDER = ?", idShopOrder, idShopOrder)

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?notif=update_success", 303)

	}

}