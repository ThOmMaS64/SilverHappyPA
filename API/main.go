package main

import (
	"API/handlersBack"
	"API/handlersFront"
	"database/sql"
	"log"
	"net/http"

	_ "github.com/go-sql-driver/mysql"
)

func main() {

	database, openError := sql.Open("mysql", "root:root@tcp(127.0.0.1:3306)/silverhappy3")

	if openError != nil{
		log.Fatal("Erreur lors de l'ouverture de la bdd : ", openError)
	}
	defer database.Close()

	connectError := database.Ping()

	if connectError != nil{
		log.Fatal("Erreur lors de la connexion à la bdd : ", connectError)
	}

	http.HandleFunc("/login", handlersFront.Login(database))
	http.HandleFunc("/registrationCustomer", handlersFront.RegistrationCustomer(database))
	http.HandleFunc("/registrationProvider", handlersFront.RegistrationProvider(database))
	http.HandleFunc("/resetPassword", handlersFront.ResetPassword(database))
	http.HandleFunc("/modifyParameters", handlersFront.ModifyParameters(database))
	http.HandleFunc("/changeLanguage", handlersFront.ChangeLanguage(database))
	http.HandleFunc("/contactForm", handlersFront.ContactForm(database))
	http.HandleFunc("/showDefaultAdvicesPage", handlersFront.ShowDefaultAdvicesPage(database))
	http.HandleFunc("/showPersonalizedAdvicesPage", handlersFront.ShowPersonalizedAdvicesPage(database))
	http.HandleFunc("/saveUnsaveAdvice", handlersFront.SaveUnsaveAdvice(database))
	http.HandleFunc("/updateProfilPicture", handlersFront.UpdateProfilPicture(database))
	http.HandleFunc("/showSavedAdvices", handlersFront.ShowSavedAdvices(database))
	http.HandleFunc("/getVisitedPageData", handlersFront.GetVisitedPageData(database))
	http.HandleFunc("/personalizeKeyWordDescription", handlersFront.PersonalizeKeyWordDescription(database))
	http.HandleFunc("/showUpdatedData", handlersFront.ShowUpdatedData(database))
	http.HandleFunc("/getDataPutInSession", handlersFront.GetDataPutInSession(database))

	http.HandleFunc("/showProductsDefaultData", handlersBack.ShowProductsDefaultData(database))
	http.HandleFunc("/showProductsPersonalizedData", handlersBack.ShowProductsPersonalizedData(database))
	http.HandleFunc("/showEventsDefaultData", handlersBack.ShowEventsDefaultData(database))
	http.HandleFunc("/showEventsPersonalizedData", handlersBack.ShowEventsPersonalizedData(database))
	http.HandleFunc("/showAdvicesDefaultData", handlersBack.ShowAdvicesDefaultData(database))
	http.HandleFunc("/showAdvicesPersonalizedData", handlersBack.ShowAdvicesPersonalizedData(database))
	http.HandleFunc("/showUsersDefaultData", handlersBack.ShowUsersDefaultData(database))
	http.HandleFunc("/showUsersPersonalizedData", handlersBack.ShowUsersPersonalizedData(database))
	http.HandleFunc("/updateUsersData", handlersBack.UpdateUsersData(database))
	http.HandleFunc("/banUser", handlersBack.BanUser(database))
	http.HandleFunc("/deleteUser", handlersBack.DeleteUser(database))
	http.HandleFunc("/updateAdviceData", handlersBack.UpdateAdviceData(database))
	http.HandleFunc("/updateEventData", handlersBack.UpdateEventData(database))
	http.HandleFunc("/updateProductData", handlersBack.UpdateProductData(database))
	http.HandleFunc("/deleteAdvice", handlersBack.DeleteAdvice(database))
	http.HandleFunc("/deleteEvent", handlersBack.DeleteEvent(database))
	http.HandleFunc("/deleteProduct", handlersBack.DeleteProduct(database))
	http.HandleFunc("/addAdvice", handlersBack.AddAdvice(database))
	http.HandleFunc("/addEvent", handlersBack.AddEvent(database))
	http.HandleFunc("/addProduct", handlersBack.AddProduct(database))
	http.HandleFunc("/showMessagesDefaultData", handlersBack.ShowMessagesDefaultData(database))
	http.HandleFunc("/deleteMessage", handlersBack.DeleteMessage(database))
	http.HandleFunc("/updateMessageStatus", handlersBack.UpdateMessageStatus(database))

	listenError := http.ListenAndServe(":8081", nil)

	if listenError != nil {

		log.Fatal("Erreur lors du démarrage du serveur : ", listenError)

	}

}