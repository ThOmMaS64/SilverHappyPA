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
const oneSignalAPIKey = "os_v2_app_mzkmy6k36rcefakfho5b3dhkglfsv2nmxj4um4fhvtw22ivasjizj37l36uuadnp2i2wspz4zfvl4rzmg7gzx7ro2oklxmx5lbhpvcq"

func startSendingNotifications(database *sql.DB) {

    for {

        checkAndSendNotifications(database)
        time.Sleep(1 * time.Minute)

    }

}

func checkAndSendNotifications(database *sql.DB) {

    timeIn14Minutes :=  time.Now().Add(14 * time.Minute)
    timeIn15Minutes :=  time.Now().Add(15 * time.Minute)

    fmt.Println("Heure Go :", time.Now())
    fmt.Println("Recherche entre :", timeIn14Minutes.Format("2006-01-02 15:04:05"), "et", timeIn15Minutes.Format("2006-01-02 15:04:05"))

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

        fmt.Printf("→ Notification à envoyer : idUser=%d, service=%s, heure=%s\n", idUser, serviceType, startTime)
        sendPushNotification(idUser, serviceType, startTime)

    }

}

func sendPushNotification(idUser int, serviceType string, startTime string) {

    fmt.Printf("  [OneSignal] Envoi à idUser=%d...\n", idUser)

    payload := map[string]any{
        "app_id": oneSignalAppID,
        "include_aliases": map[string]any{
            "external_id": []string{fmt.Sprintf("%d", idUser)},
        },
        "target_channel": "push",
        "url": "http://localhost/ProjetAnnuel/index.php",
        "headings": map[string]string{"en": "Rappel de prestation", "fr": "Rappel de prestation"},
        "contents": map[string]string{"en": fmt.Sprintf("Votre prestation \"%s\" commence dans 15 minutes (%s).", serviceType, startTime),},
    }

    body, _ := json.Marshal(payload)

    req, err := http.NewRequest("POST", "https://api.onesignal.com/notifications", bytes.NewBuffer(body))
    if err != nil {
        fmt.Println("  [OneSignal] ERREUR création requête :", err)
        return
    }
    req.Header.Set("Content-Type", "application/json")
    req.Header.Set("Authorization", "Key "+oneSignalAPIKey)

    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        fmt.Println("  [OneSignal] ERREUR envoi requête :", err)
        return
    }
    defer resp.Body.Close()

    fmt.Println("  [OneSignal] Statut réponse :", resp.Status)

    var result map[string]any
    json.NewDecoder(resp.Body).Decode(&result)
    fmt.Println("  [OneSignal] Réponse body :", result)

}