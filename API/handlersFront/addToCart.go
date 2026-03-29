package handlersFront

import (
	"database/sql"
	"net/http"
	"strconv"
)

func AddToCart(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		idProduct := r.FormValue("id_product")
		quantityStr := r.FormValue("quantity")

		quantity, _ := strconv.Atoi(quantityStr)

		var name string
		var price float64

		row := database.QueryRow("SELECT name, price FROM PRODUCT WHERE ID_PRODUCT = ?", idProduct)
	
		err := row.Scan(&name, &price)

		if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/store.php?error=add_error", 303)
			return

		}

		var idConsumer int

		row = database.QueryRow("SELECT ID_CONSUMER FROM CONSUMER WHERE ID_USER = ?", id)
	
		err = row.Scan(&idConsumer)

		if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/store.php?error=add_error", 303)
			return

		}

		var idShopOrder int

		row = database.QueryRow("SELECT ID_SHOP_ORDER FROM SHOP_ORDER WHERE ID_CONSUMER = ? AND status = 0", idConsumer)
	
		err = row.Scan(&idShopOrder)

		if err == sql.ErrNoRows{

			insertStatement, insertError := database.Prepare("INSERT INTO SHOP_ORDER(order_date, total_price, vat, status, ID_CONSUMER) VALUES(NOW(), 0, 20.0, 0, ?)")

			if insertError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/store.php?error=add_error", 303)
				return	

			}
			defer insertStatement.Close()

			insertStatementExecResult, insertStatementExecError := insertStatement.Exec(idConsumer)

			lastId, _ := insertStatementExecResult.LastInsertId()
			idShopOrder = int(lastId)

			if insertStatementExecError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/store.php?error=add_error", 303)
				return	

			}

		}else if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/store.php?error=add_error", 303)
			return

		}

		var idOrderLine int

		row = database.QueryRow("SELECT ID_ORDER_LINE FROM ORDER_LINE WHERE ID_SHOP_ORDER = ? AND ID_PRODUCT = ?", idShopOrder, idProduct)
	
		err = row.Scan(&idOrderLine)

		totalLinePrice := price * float64(quantity)

		if err == sql.ErrNoRows{

			insertStatement, insertError := database.Prepare("INSERT INTO ORDER_LINE(ID_SHOP_ORDER, ID_PRODUCT, quantity, price) VALUES(?, ?, ?, ?)")

			if insertError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/store.php?error=add_error", 303)
				return	

			}
			defer insertStatement.Close()

			_, insertStatementExecError := insertStatement.Exec(idShopOrder, idProduct, quantity, totalLinePrice)

			if insertStatementExecError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/store.php?error=add_error", 303)
				return	

			}

		}else if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/store.php?error=add_error", 303)
			return

		}else if err == nil{

			updateStatement, updateError := database.Prepare("UPDATE ORDER_LINE SET quantity = quantity + ?, price = price + ? WHERE ID_ORDER_LINE = ?")

			if updateError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/store.php?error=add_error", 303)
				return	

			}
			defer updateStatement.Close()

			_, updateStatementExecError := updateStatement.Exec(quantity, totalLinePrice, idOrderLine)

			if updateStatementExecError != nil{

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/store.php?error=add_error", 303)
				return	

			}

		}

		database.Exec("UPDATE SHOP_ORDER SET total_price = (SELECT SUM(price) FROM ORDER_LINE WHERE ID_SHOP_ORDER = ?) WHERE ID_SHOP_ORDER = ?", idShopOrder, idShopOrder)

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/store.php?notif=add_success", 303)	

	}
	
}