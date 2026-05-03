package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

func ShowPersonalizedGradesIGot(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		id := r.FormValue("id")
		sort := r.FormValue("sort")

		response := GradeResponse{
			Grades: []GradeReceived{},
		}

		var args []any

		basicQuery := "SELECT GRADE.ID_GRADE, GRADE.grade, GRADE.description, SERVICE.type, CONSUMER.ID_CONSUMER, USER_.name, USER_.surname FROM GRADE JOIN INTERVENTION ON GRADE.ID_INTERVENTION = INTERVENTION.ID_INTERVENTION JOIN DO ON INTERVENTION.ID_INTERVENTION = DO.ID_INTERVENTION JOIN SERVICE_PROVIDER ON DO.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER JOIN SERVICE ON INTERVENTION.ID_SERVICE = SERVICE.ID_SERVICE JOIN CONSUMER ON GRADE.ID_CONSUMER = CONSUMER.ID_CONSUMER JOIN USER_ ON CONSUMER.ID_USER = USER_.ID_USER WHERE SERVICE_PROVIDER.ID_USER = ?"
		args = append(args, id)

		if sort == "1" {

			basicQuery += " ORDER BY GRADE.date DESC"

		} else if sort == "2" {

			basicQuery += " ORDER BY GRADE.date ASC"

		} else if sort == "3" {

			basicQuery += " ORDER BY GRADE.grade DESC"

		} else if sort == "4" {

			basicQuery += " ORDER BY GRADE.grade ASC"

		}

		rows, errSelectGrades := database.Query(basicQuery, args...)

		if errSelectGrades != nil {

			response.Error = "Erreur lors de la récupération des avis."
			json.NewEncoder(w).Encode(response)
			return

		}

		defer rows.Close()

		for rows.Next() {

			var grade GradeReceived
			var description sql.NullString

			err := rows.Scan(&grade.IdGrade, &grade.Grade, &description, &grade.ServiceType, &grade.IdConsumer, &grade.ConsumerName, &grade.ConsumerSurname)

			if err == nil {

				grade.Description = description.String
				response.Grades = append(response.Grades, grade)

			}
		}

		json.NewEncoder(w).Encode(response)

	}

}