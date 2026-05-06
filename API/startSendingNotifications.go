package main

import (
	"bytes"
	"database/sql"
	"encoding/json"
	"fmt"
	"net/http"
	"time"
)

const oneSignalAppID  = "6654cc79-5bf4-4442-8145-3bba1d8cea32"
const oneSignalAPIKey = "os_v2_app_mzkmy6k36rcefakfho5b3dhkgiw4fqzqp4buok47dcvusenik6o2p7yzp44s6ygiddnp5q4g2y4u44fxmiqc3scvksyfyhnfjqr73qq"

func startSendingNotifications(database *sql.DB) {

    for {

        checkAndSendNotifications(database)
        time.Sleep(1 * time.Minute)

    }

}

func checkAndSendNotifications(database *sql.DB) {

    timeIn14Minutes :=  time.Now().Add(14 * time.Minute)
    timeIn15Minutes :=  time.Now().Add(15 * time.Minute)

    rows, err := database.Query("SELECT CONSUMER.ID_USER, SERVICE.type, SERVICE_SLOT.start_time FROM SERVICE_BOOKING INNER JOIN SERVICE_SLOT ON SERVICE_BOOKING.ID_SERVICE_SLOT = SERVICE_SLOT.ID_SERVICE_SLOT INNER JOIN SERVICE ON SERVICE_SLOT.ID_SERVICE = SERVICE.ID_SERVICE INNER JOIN CONSUMER ON SERVICE_BOOKING.ID_CONSUMER = CONSUMER.ID_CONSUMER WHERE SERVICE_SLOT.start_time > ? AND SERVICE_SLOT.start_time <= ?", timeIn14Minutes.Format("2006-01-02 15:04:05"), timeIn15Minutes.Format("2006-01-02 15:04:05"))

    if err != nil {

        return

    }
    defer rows.Close()

    for rows.Next() {

        var idUser int
        var serviceType string
        var startTime string

		err := rows.Scan(&idUser, &serviceType, &startTime)
		
        if err != nil {

            continue

        }

        sendPushNotification(idUser, serviceType, startTime)

    }

}

func sendPushNotification(idUser int, serviceType string, startTime string) {

    payload := map[string]any{
        "app_id": oneSignalAppID,
        "include_aliases": map[string]any{
            "external_id": []string{fmt.Sprintf("%d", idUser)},
        },
        "target_channel": "push",
        "headings": map[string]string{"en": "Rappel de prestation", "fr": "Rappel de prestation"},
        "contents": map[string]string{"en": fmt.Sprintf("Votre prestation \"%s\" commence dans 15 minutes (%s).", serviceType, startTime),},
    }

    body, _ := json.Marshal(payload)

    req, _ := http.NewRequest("POST", "https://onesignal.com/api/v1/notifications", bytes.NewBuffer(body))
    req.Header.Set("Content-Type", "application/json")
    req.Header.Set("Authorization", "Basic "+oneSignalAPIKey)

    client := &http.Client{}
    client.Do(req)

}