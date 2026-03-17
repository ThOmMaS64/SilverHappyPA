package handlersBack

import (
	"database/sql"
	"net/http"
)

func UpdateUsersData(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		id := r.FormValue("id")
		username:= r.FormValue("username")
		name:= r.FormValue("name")
		surname:= r.FormValue("surname")
		email:= r.FormValue("email")
		birth_date:= r.FormValue("birth_date")
		city:= r.FormValue("city")
		street:= r.FormValue("street")
		nb_street:= r.FormValue("nb_street")
		postal_code:= r.FormValue("postal_code")
		status:= r.FormValue("status")
		date_inscription:= r.FormValue("date_inscription")
		description := r.FormValue("description")
		key_word1 := r.FormValue("keyWord1")
		key_word2 := r.FormValue("keyWord2")
		key_word3 := r.FormValue("keyWord3")

		updateStatement, updateError := database.Prepare("UPDATE USER_ SET username = ?, name = ?, surname = ?, email = ?, city = ?, street = ?, nb_street = ?, postal_code = ?, description = ?, keyWord1 = ?, keyWord2 = ?, keyWord3 = ?, status = ?, date_inscription = ? WHERE ID_USER = ?")

		if updateError != nil{

			http.Error(w, "Erreur lors de la mise à jour des données dans la base de données 1", 500)
			return	

		}
		defer updateStatement.Close()

		_, updateStatementExecError := updateStatement.Exec(username, name, surname, email, city, street, nb_street, postal_code, description, key_word1, key_word2, key_word3, status, date_inscription, id)

		if updateStatementExecError != nil{

			http.Error(w, "Erreur lors de la mise à jour des données dans la base de données 2", 500)
			return	

		}

		updateStatement2, updateError2 := database.Prepare("UPDATE CONSUMER SET birth_date = ? WHERE ID_USER = ?")

		if updateError2 != nil{

			http.Error(w, "Erreur lors de la mise à jour des données dans la base de données 3", 500)
			return	

		}
		defer updateStatement2.Close()

		_, updateStatementExecError2 := updateStatement2.Exec(birth_date, id)

		if updateStatementExecError2 != nil{

			http.Error(w, "Erreur lors de la mise à jour des données dans la base de données 4", 500)
			return	

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/backoffice/index.php#pageusers", 303)

	}

}