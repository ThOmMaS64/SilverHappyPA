package handlersFront

import (
	"database/sql"
	"fmt"
	"io"
	"net/http"
	"os"
	"path/filepath"
	"strings"
)

func AddServiceProviderDocuments(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		err := r.ParseMultipartForm(20 << 20)

		if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=add_error", 303)
			return

		}

		idServiceProvider := r.FormValue("id_service_provider")
		serviceType := r.FormValue("service_type")

		if idServiceProvider == "" || serviceType == "" {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=missing_field", 303)
			return

		}

		rows, errQuery := database.Query("SELECT REQUIRED_DOCUMENT.name FROM REQUIRED_DOCUMENT JOIN SERVICE_DOCUMENT ON REQUIRED_DOCUMENT.ID_REQUIRED_DOCUMENT = SERVICE_DOCUMENT.ID_REQUIRED_DOCUMENT JOIN SERVICE ON SERVICE_DOCUMENT.ID_SERVICE = SERVICE.ID_SERVICE WHERE SERVICE.type = ? ORDER BY REQUIRED_DOCUMENT.name ASC", serviceType)

		if errQuery != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system1", 303)
			return
			
		}
		defer rows.Close()

		var requiredDocs []string

		for rows.Next() {

			var docName string

			err := rows.Scan(&docName);

			if err != nil {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system2", 303)
				return

			}
			requiredDocs = append(requiredDocs, docName)
		}

		for _, docName := range requiredDocs {

			var validatedCount int

			row := database.QueryRow("SELECT COUNT(*) FROM SERVICE_PROVIDER_DOCUMENT WHERE ID_SERVICE_PROVIDER = ? AND type = ? AND status = 1", idServiceProvider, docName,)
			
			err := row.Scan(&validatedCount)

			if err != nil {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system3", 303)
				return

			}

			if validatedCount > 0 {

				continue
				
			}

			fieldKey := "doc_" + strings.ReplaceAll(strings.ToLower(docName), " ", "_")

			file, header, errFile := r.FormFile(fieldKey)

			if errFile != nil {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=missing_file", 303)
				return
				
			}
			defer file.Close()

			fileBytes, errRead := io.ReadAll(file)

			if errRead != nil {
				http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system4", 303)
				return
			}

			ext := filepath.Ext(header.Filename)
			docNameNoSpace := strings.ReplaceAll(strings.ToLower(docName), " ", "_")
			filename := fmt.Sprintf("provider_%s_%s_%s", idServiceProvider, docNameNoSpace, ext)
			pathName := fmt.Sprintf("../data/documents/%s", filename)

			if err := os.WriteFile(pathName, fileBytes, 0644); err != nil {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system4", 303)
				return

			}

			var waitingValidationID int

			row = database.QueryRow("SELECT ID_DOCUMENT FROM SERVICE_PROVIDER_DOCUMENT WHERE ID_SERVICE_PROVIDER = ? AND type = ? AND status = 0", idServiceProvider, docName,)
			errWaitingValidation := row.Scan(&waitingValidationID)

			if errWaitingValidation == nil {

				_, errUpdate := database.Exec("UPDATE SERVICE_PROVIDER_DOCUMENT SET docPath = ?, uploadDate = CURDATE() WHERE ID_DOCUMENT = ?", pathName, waitingValidationID,)
				
				if errUpdate != nil {

					http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system5", 303)
					return

				}

			} else if errWaitingValidation == sql.ErrNoRows {

				_, errInsert := database.Exec("INSERT INTO SERVICE_PROVIDER_DOCUMENT (ID_SERVICE_PROVIDER, type, docPath, uploadDate, status) VALUES (?, ?, ?, CURDATE(), 0)", idServiceProvider, docName, pathName,)
				
				if errInsert != nil {
					
					http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system6", 303)
					return
				
				}

			} else {

				http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system7", 303)
				return
				
			}
		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?success=documents_sent", 303)

	}

}