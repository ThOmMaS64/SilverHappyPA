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

	database, openError := sql.Open("mysql", "root:root@tcp(127.0.0.1:3306)/silverhappy4")

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
	http.HandleFunc("/showDefaultEventsPage", handlersFront.ShowDefaultEventsPage(database))
	http.HandleFunc("/showPersonalizedEventsPage", handlersFront.ShowPersonalizedEventsPage(database))
	http.HandleFunc("/saveUnsaveEvent", handlersFront.SaveUnsaveEvent(database))
	http.HandleFunc("/showSavedEvent", handlersFront.ShowSavedEvent(database))
	http.HandleFunc("/showRegisteredEvent", handlersFront.ShowRegisteredEvent(database))
	http.HandleFunc("/showDefaultStorePage", handlersFront.ShowDefaultStorePage(database))
	http.HandleFunc("/showPersonalizedStorePage", handlersFront.ShowPersonalizedStorePage(database))
	http.HandleFunc("/addToCart", handlersFront.AddToCart(database))
	http.HandleFunc("/showCart", handlersFront.ShowCart(database))
	http.HandleFunc("/updateItemData", handlersFront.UpdateItemData(database))
	http.HandleFunc("/deleteItem", handlersFront.DeleteItem(database))
	http.HandleFunc("/showDefaultServicesPage", handlersFront.ShowDefaultServicesPage(database))
	http.HandleFunc("/showPersonalizedServicesPage", handlersFront.ShowPersonalizedServicesPage(database))
	http.HandleFunc("/saveUnsaveService", handlersFront.SaveUnsaveService(database))
	http.HandleFunc("/showSavedService", handlersFront.ShowSavedService(database))
	http.HandleFunc("/generateEventInvoice", handlersFront.GenerateEventInvoice(database))
	http.HandleFunc("/generateStoreInvoice", handlersFront.GenerateStoreInvoice(database))
	http.HandleFunc("/generateServiceInvoice", handlersFront.GenerateServiceInvoice(database))
	http.HandleFunc("/showDiscussions", handlersFront.ShowDiscussions(database))
	http.HandleFunc("/showMessages", handlersFront.ShowMessages(database))
	http.HandleFunc("/sendMessage", handlersFront.SendMessage(database))
	http.HandleFunc("/sendQuote", handlersFront.SendQuote(database))
	http.HandleFunc("/openOrCreateDiscussion", handlersFront.OpenOrCreateDiscussion(database))
	http.HandleFunc("/getServicesForQuote", handlersFront.GetServicesForQuote(database))
	http.HandleFunc("/showRegisteredService", handlersFront.ShowRegisteredService(database))
	http.HandleFunc("/showInvoices", handlersFront.ShowInvoices(database))
	http.HandleFunc("/showQuotes", handlersFront.ShowQuotes(database))
	http.HandleFunc("/acceptQuote", handlersFront.AcceptQuote(database))
	http.HandleFunc("/refuseQuote", handlersFront.RefuseQuote(database))
	http.HandleFunc("/generateQuoteServiceInvoice", handlersFront.GenerateQuoteServiceInvoice(database))
	http.HandleFunc("/showRegisteredServicesCalendar", handlersFront.ShowRegisteredServicesCalendar(database))
	http.HandleFunc("/showServiceProviderSlotsPlanning", handlersFront.ShowServiceProviderSlotsPlanning(database))
	http.HandleFunc("/showServiceProviderQuotesPlanning", handlersFront.ShowServiceProviderQuotesPlanning(database))

	http.HandleFunc("/showInvoicesDefaultData", handlersBack.ShowInvoicesDefaultData(database))
	http.HandleFunc("/showInvoicesPersonalizedData", handlersBack.ShowInvoicesPersonalizedData(database))
	http.HandleFunc("/showNotificationsDefaultData", handlersBack.ShowNotificationsDefaultData(database))
	http.HandleFunc("/showNotificationsPersonalizedData", handlersBack.ShowNotificationsPersonalizedData(database))
	http.HandleFunc("/showServicesDefaultData", handlersBack.ShowServicesDefaultData(database))
	http.HandleFunc("/showServicesPersonalizedData", handlersBack.ShowServicesPersonalizedData(database))
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
	http.HandleFunc("/updateServiceData", handlersBack.UpdateServiceData(database))
	http.HandleFunc("/updateNotificationData", handlersBack.UpdateNotificationData(database))
	http.HandleFunc("/deleteAdvice", handlersBack.DeleteAdvice(database))
	http.HandleFunc("/deleteEvent", handlersBack.DeleteEvent(database))
	http.HandleFunc("/deleteProduct", handlersBack.DeleteProduct(database))
	http.HandleFunc("/deleteService", handlersBack.DeleteService(database))
	http.HandleFunc("/deleteNotification", handlersBack.DeleteNotification(database))
	http.HandleFunc("/addAdvice", handlersBack.AddAdvice(database))
	http.HandleFunc("/addEvent", handlersBack.AddEvent(database))
	http.HandleFunc("/addProduct", handlersBack.AddProduct(database))
	http.HandleFunc("/addService", handlersBack.AddService(database))
	http.HandleFunc("/addNotification", handlersBack.AddNotification(database))
	http.HandleFunc("/showMessagesDefaultData", handlersBack.ShowMessagesDefaultData(database))
	http.HandleFunc("/deleteMessage", handlersBack.DeleteMessage(database))
	http.HandleFunc("/updateMessageStatus", handlersBack.UpdateMessageStatus(database))
	http.HandleFunc("/showRequestsDefaultData", handlersBack.ShowRequestsDefaultData(database))
	http.HandleFunc("/deleteRequest", handlersBack.DeleteRequest(database))
	http.HandleFunc("/showRequestsPersonalizedData", handlersBack.ShowRequestsPersonalizedData(database))
	http.HandleFunc("/showServiceProvidersDefaultData", handlersBack.ShowServiceProvidersDefaultData(database))
	http.HandleFunc("/showServiceProvidersPersonalizedData", handlersBack.ShowServiceProvidersPersonalizedData(database))
	http.HandleFunc("/loginBack", handlersBack.LoginBack(database))

	listenError := http.ListenAndServe(":8081", nil)

	if listenError != nil {

		log.Fatal("Erreur lors du démarrage du serveur : ", listenError)

	}

}