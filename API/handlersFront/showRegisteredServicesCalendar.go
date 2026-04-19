package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ServiceCalendar struct{

	ServiceType string `json:"service_type"`
	StartTime string `json:"start_time"`
	EndTime string `json:"end_time"`
	IsAtConsumerHome bool `json:"is_at_consumer_home"`
	City string `json:"city"`
	Street string `json:"street"`
	NbStreet string `json:"nb_street"`
	PostalCode string `json:"postal_code"`

}

type ResponseServiceCalendar struct{

	Services []ServiceCalendar `json:"services"`
	Error string `json:"error"`

}

func ShowRegisteredServicesCalendar(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseServiceCalendar{
			Services: []ServiceCalendar{},
		}

		id := r.FormValue("id")

		rowSelectServices, errSelectServices := database.Query("SELECT SERVICE.type, SERVICE_SLOT.start_time, SERVICE_SLOT.end_time, SERVICE.is_at_consumer_home, COALESCE(WORK_ADDRESS.city, ''), COALESCE(WORK_ADDRESS.street, ''), COALESCE(WORK_ADDRESS.nb_street, ''), COALESCE(WORK_ADDRESS.postal_code, '') FROM SERVICE_BOOKING INNER JOIN SERVICE_SLOT ON SERVICE_BOOKING.ID_SERVICE_SLOT = SERVICE_SLOT.ID_SERVICE_SLOT INNER JOIN SERVICE ON SERVICE_SLOT.ID_SERVICE = SERVICE.ID_SERVICE LEFT JOIN WORK_ADDRESS ON SERVICE.ID_WORK_ADDRESS = WORK_ADDRESS.ID_WORK_ADDRESS WHERE SERVICE_BOOKING.ID_CONSUMER = (SELECT ID_CONSUMER FROM CONSUMER WHERE ID_USER = ?)", id)
	
		if errSelectServices != nil{

			w.WriteHeader(500)
			response.Error = "Erreur lors de la récupération des Services depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}

		defer rowSelectServices.Close()

		for rowSelectServices.Next(){

			var service ServiceCalendar

			err := rowSelectServices.Scan(&service.ServiceType, &service.StartTime, &service.EndTime, &service.IsAtConsumerHome, &service.City, &service.Street, &service.NbStreet, &service.PostalCode)

			if err == nil{

				response.Services = append(response.Services, service)

			}
		}

		json.NewEncoder(w).Encode(response)
		 
	}

}