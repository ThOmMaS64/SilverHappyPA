package handlersFront

import (
	"database/sql"
	"fmt"
	"io"
	"net/http"
	"os"
	"path/filepath"
	"strconv"
	"strings"
)

func AddServiceProviderDocuments(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		documentOrNo := r.FormValue("documentOrNo")
		pricingType := r.FormValue("pricing_type")

		cost := r.FormValue("cost")
		var finalCost sql.NullFloat64
		if cost != "" {
			val, _ := strconv.ParseFloat(cost, 64)
			finalCost = sql.NullFloat64{Float64: val, Valid: true}
		}

		status := 0
		if documentOrNo == "0"{

			status = 1

		}

		idServiceProvider := r.FormValue("id_service_provider")
		serviceType := r.FormValue("service_type")

		var idService int

		row := database.QueryRow("SELECT ID_SERVICE FROM SERVICE WHERE type = ?", serviceType)
		errService := row.Scan(&idService)

		if errService != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system1", 303)
			return

		}

		insertStatement, insertError := database.Prepare("INSERT INTO OFFER(ID_SERVICE_PROVIDER, ID_SERVICE, pricing_type, cost, status) VALUES(?, ?, ?, ?, ?)")

		if insertError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system2", 303)
			return

		}

		_, insertExecError := insertStatement.Exec(idServiceProvider, idService, pricingType, finalCost, status)

		if insertExecError != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=system2", 303)
			return

		}

		city := r.FormValue("city")
		street := r.FormValue("street")
		nbStreet := r.FormValue("nb_street")
		postalCode := r.FormValue("postal_code")

		if city != "" && street != "" && nbStreet != "" && postalCode != "" {

			var existingWorkAddressID int

			rowCheck := database.QueryRow("SELECT ID_WORK_ADDRESS FROM WORK_ADDRESS WHERE city = ? AND street = ? AND nb_street = ? AND postal_code = ? AND ID_WORK_ADDRESS IN (SELECT ID_WORK_ADDRESS FROM OFFER WHERE ID_SERVICE_PROVIDER = ?)", city, street, nbStreet, postalCode, idServiceProvider)

			errCheck := rowCheck.Scan(&existingWorkAddressID)

			if errCheck == sql.ErrNoRows {

				res, errInsertAddr := database.Exec("INSERT INTO WORK_ADDRESS (city, street, nb_street, postal_code) VALUES (?, ?, ?, ?)", city, street, nbStreet, postalCode)

				if errInsertAddr == nil {

					newID, _ := res.LastInsertId()
					database.Exec("UPDATE OFFER SET ID_WORK_ADDRESS = ? WHERE ID_SERVICE_PROVIDER = ? AND ID_SERVICE = ?", newID, idServiceProvider, idService)

				}

			} else if errCheck == nil {

				database.Exec("UPDATE OFFER SET ID_WORK_ADDRESS = ? WHERE ID_SERVICE_PROVIDER = ? AND ID_SERVICE = ?", existingWorkAddressID, idServiceProvider, idService)

			}

		}

		if documentOrNo == "0" {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?notif=new_service", 303)
			return

		}

		err := r.ParseMultipartForm(20 << 20)

		if err != nil {

			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?error=add_error", 303)
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

		allValidated := true

		for _, docName := range requiredDocs {
			var count int
			database.QueryRow("SELECT COUNT(*) FROM SERVICE_PROVIDER_DOCUMENT WHERE ID_SERVICE_PROVIDER = ? AND type = ? AND status = 1", idServiceProvider, docName).Scan(&count)

			if count == 0 {
				allValidated = false
				break
			}
		}

		if allValidated {

			database.Exec("UPDATE OFFER SET status = 1 WHERE ID_SERVICE_PROVIDER = ? AND ID_SERVICE = ?", idServiceProvider, idService)
			http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?notif=new_service", 303)
			return
			
		}

		http.Redirect(w, r, "http://localhost/ProjetAnnuel/dashboard.php?notif=documents_sent", 303)

	}

}