package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ServiceToGrade struct{

	IdIntervention int `json:"id_intervention"`
	Type string `json:"type"`
	ServiceProviderName string `json:"service_provider_name"`
	ServiceProviderSurname string `json:"service_provider_surname"`

}

type ResponseServiceToGrade struct {

	Services []ServiceToGrade `json:"services"`
	Error string `json:"error"`

}

func ShowDoneAndNotGradedServices(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		id := r.FormValue("id")

		var response ResponseServiceToGrade

		rowSelectServices, errSelectServices := database.Query("SELECT INTERVENTION.ID_INTERVENTION, SERVICE.type, USER_.name, USER_.surname FROM INTERVENTION JOIN CALL_ ON INTERVENTION.ID_INTERVENTION = CALL_.ID_INTERVENTION JOIN CONSUMER ON CALL_.ID_CONSUMER = CONSUMER.ID_CONSUMER JOIN SERVICE ON INTERVENTION.ID_SERVICE = SERVICE.ID_SERVICE JOIN DO ON INTERVENTION.ID_INTERVENTION = DO.ID_INTERVENTION JOIN SERVICE_PROVIDER ON DO.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER JOIN USER_ ON SERVICE_PROVIDER.ID_USER = USER_.ID_USER LEFT JOIN GRADE ON INTERVENTION.ID_INTERVENTION = GRADE.ID_INTERVENTION AND CONSUMER.ID_CONSUMER = GRADE.ID_CONSUMER WHERE CONSUMER.ID_USER = ? AND GRADE.ID_GRADE IS NULL AND INTERVENTION.end_date < NOW()", id)
	
		if errSelectServices != nil{

			response.Error = "Erreur lors de la récupération des prestations réalisées"
			json.NewEncoder(w).Encode(response)
			return

		}

		defer rowSelectServices.Close()

		for rowSelectServices.Next() {

			var service ServiceToGrade

			err := rowSelectServices.Scan(&service.IdIntervention, &service.Type, &service.ServiceProviderName, &service.ServiceProviderSurname)

			if err == nil {

				response.Services = append(response.Services, service)

			}
		}

		json.NewEncoder(w).Encode(response)
		 
	}

}