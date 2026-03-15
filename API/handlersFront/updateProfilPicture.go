package handlersFront

import (
	"database/sql"
	"io"
	"net/http"
	"os"
	"path/filepath"
)

func UpdateProfilPicture(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		file, header, err := r.FormFile("myProfilePicture")

		if err != nil {

			http.Error(w, "Erreur système 1", 500)
			return 

		}
		defer file.Close()

		id := r.FormValue("id")

		filename := "user_" + id + filepath.Ext(header.Filename)

		pathName := "../data/profils/" + filename

		out, err := os.Create(pathName)

		if err != nil {

			http.Error(w, "Erreur système 2", 500)
			return 

		}
		defer out.Close()

		io.Copy(out, file)

		updateStatement, updateError := database.Prepare("UPDATE USER_ SET profilePicture = ? WHERE ID_USER = ?")

		if updateError != nil {

			http.Error(w, "Erreur système 3", 500)
			return 

		}
		defer updateStatement.Close()

		_, updateExecError := updateStatement.Exec(filename, id)

		if updateExecError != nil {

			http.Error(w, "Erreur système 4", 500)
			return 

		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/profile.php?notif=profile_picture_changement_success",303)
		 
	}

}