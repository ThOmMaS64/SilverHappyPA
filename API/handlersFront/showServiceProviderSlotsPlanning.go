package handlersFront

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type ServiceProviderCalendar struct{

	ServiceType string `json:"service_type"`
	StartTime string `json:"start_time"`
	EndTime string `json:"end_time"`
	IsAtConsumerHome bool `json:"is_at_consumer_home"`
	City string `json:"city"`
	Street string `json:"street"`
	NbStreet string `json:"nb_street"`
	PostalCode string `json:"postal_code"`

}

type ResponseServiceProviderCalendarSlots struct{

	Slots []ServiceProviderCalendar `json:"slots"`
	Error string `json:"error"`

}

func ShowServiceProviderSlotsPlanning(database *sql.DB) http.HandlerFunc {

	return func(w http.ResponseWriter, r *http.Request) {

		w.Header().Set("Content-Type", "application/json")

		response := ResponseServiceProviderCalendarSlots{
			Slots: []ServiceProviderCalendar{},
		}

		id := r.FormValue("id")

		rowSelectServices, errSelectServices := database.Query("SELECT SERVICE.type, SERVICE_SLOT.start_time, SERVICE_SLOT.end_time, SERVICE.is_at_consumer_home, COALESCE(WORK_ADDRESS.city, ''), COALESCE(WORK_ADDRESS.street, ''), COALESCE(WORK_ADDRESS.nb_street, ''), COALESCE(WORK_ADDRESS.postal_code, '') FROM SERVICE_SLOT INNER JOIN SERVICE ON SERVICE_SLOT.ID_SERVICE = SERVICE.ID_SERVICE LEFT JOIN WORK_ADDRESS ON SERVICE.ID_WORK_ADDRESS = WORK_ADDRESS.ID_WORK_ADDRESS WHERE SERVICE_SLOT.ID_SERVICE_PROVIDER = (SELECT ID_SERVICE_PROVIDER FROM SERVICE_PROVIDER WHERE ID_USER = ?) AND SERVICE_SLOT.is_booked = 1", id)
	
		if errSelectServices != nil{

			w.WriteHeader(500)
			response.Error = "Erreur lors de la récupération des Services depuis la base de donnée."
			json.NewEncoder(w).Encode(response)
			return 

		}
		defer rowSelectServices.Close()

		for rowSelectServices.Next(){

			var service ServiceProviderCalendar

			err := rowSelectServices.Scan(&service.ServiceType, &service.StartTime, &service.EndTime, &service.IsAtConsumerHome, &service.City, &service.Street, &service.NbStreet, &service.PostalCode)

			if err == nil{

				response.Slots = append(response.Slots, service)

			}
		}

		json.NewEncoder(w).Encode(response)
		 
	}

}