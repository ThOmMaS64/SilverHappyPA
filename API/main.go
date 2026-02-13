package main

import (
	"API/handlers"
	"database/sql"
	"log"
	"net/http"

	_ "github.com/go-sql-driver/mysql"
)

func main() {

	database, openError := sql.Open("mysql", "root:root@tcp(127.0.0.1:3306)/silverhappy2")

	if openError != nil{
		log.Fatal("Erreur lors de l'ouverture de la bdd : ", openError)
	}
	defer database.Close()

	connectError := database.Ping()

	if connectError != nil{
		log.Fatal("Erreur lors de la connexion à la bdd : ", connectError)
	}

	http.HandleFunc("/login", handlers.Login(database))
	http.HandleFunc("/registrationCustomer", handlers.RegistrationCustomer(database))
	http.HandleFunc("/registrationProvider", handlers.RegistrationCustomer(database))
	http.HandleFunc("/resetPassword", handlers.ResetPassword(database))
	http.HandleFunc("/suppressionAccount", handlers.SuppressionAccount(database))

	listenError := http.ListenAndServe(":8081", nil)

	if listenError != nil {

		log.Fatal("Erreur lors du démarrage du serveur : ", listenError)

	}

}