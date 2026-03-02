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
	http.HandleFunc("/registrationProvider", handlers.RegistrationProvider(database))
	http.HandleFunc("/resetPassword", handlers.ResetPassword(database))
	http.HandleFunc("/modifyParameters", handlers.ModifyParameters(database))
	http.HandleFunc("/changeLanguage", handlers.ChangeLanguage(database))
	http.HandleFunc("/contactForm", handlers.ContactForm(database))
	http.HandleFunc("/showDefaultAdvicesPage", handlers.ShowDefaultAdvicesPage(database))
	http.HandleFunc("/showPersonalizedAdvicesPage", handlers.ShowPersonalizedAdvicesPage(database))
	http.HandleFunc("/saveUnsaveAdvice", handlers.SaveUnsaveAdvice(database))
	http.HandleFunc("/updateProfilPicture", handlers.UpdateProfilPicture(database))
	http.HandleFunc("/showSavedAdvices", handlers.ShowSavedAdvices(database))
	http.HandleFunc("/getVisitedPageData", handlers.GetVisitedPageData(database))
	http.HandleFunc("/personalizeKeyWordDescription", handlers.PersonalizeKeyWordDescription(database))
	http.HandleFunc("/showUpdatedData", handlers.ShowUpdatedData(database))

	listenError := http.ListenAndServe(":8081", nil)

	if listenError != nil {

		log.Fatal("Erreur lors du démarrage du serveur : ", listenError)

	}

}