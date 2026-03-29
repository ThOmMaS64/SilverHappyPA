package handlersFront

import (
	"database/sql"
	"net/http"
)

func DeleteItem(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		idProduct := r.FormValue("id_product")

		var idConsumer int

		row := database.QueryRow("SELECT ID_CONSUMER FROM CONSUMER WHERE ID_USER = ?", id)
	
		err := row.Scan(&idConsumer)

		if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=delete_error", 303)
			return

		}

		var idShopOrder int

		row = database.QueryRow("SELECT ID_SHOP_ORDER FROM SHOP_ORDER WHERE ID_CONSUMER = ? AND status = 0", idConsumer)
	
		err = row.Scan(&idShopOrder)

		if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=delete_error", 303)
			return

		}

		updateStatement, updateError := database.Prepare("DELETE FROM ORDER_LINE WHERE ID_PRODUCT = ? AND ID_SHOP_ORDER = ?")

		if updateError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?notif=delete_error", 303)
			return	

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(idProduct, idShopOrder)

		if updateStatementExecError != nil{

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?error=delete_error", 303)
			return	

		}

		database.Exec("UPDATE SHOP_ORDER SET total_price = (SELECT COALESCE(SUM(price),0) FROM ORDER_LINE WHERE ID_SHOP_ORDER = ?) WHERE ID_SHOP_ORDER = ?", idShopOrder, idShopOrder)

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/cart.php?notif=delete_success", 303)

	}

}