<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="icon" href="../medias/logos/logoDessin.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="style.css?v=6">
    <script src="script.js"></script>
</head>
<?php

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tableChoice'])){

    $_SESSION['tableChoice'] = (int)$_POST['tableChoice'];

}

if(!isset($_SESSION['offsetUsers'])){

    $_SESSION['offsetUsers'] = 0;

}
if(!isset($_SESSION['offsetAdvices'])){

    $_SESSION['offsetAdvices'] = 0;

}
if(!isset($_SESSION['offsetEvents'])){

    $_SESSION['offsetEvents'] = 0;

}

if(!isset($_SESSION['offsetShop'])){

    $_SESSION['offsetShop'] = 0;

}

if(!isset($_SESSION['offsetServices'])){

    $_SESSION['offsetServices'] = 0;

}

if(!isset($_SESSION['offsetServiceProviders'])){

    $_SESSION['offsetServiceProviders'] = 0;

}

if(!isset($_SESSION['offsetMessages'])){

    $_SESSION['offsetMessages'] = 0;

}

if(!isset($_SESSION['offsetRequests'])){

    $_SESSION['offsetRequests'] = 0;

}

if(!isset($_SESSION['offsetNotifs'])){

    $_SESSION['offsetNotifs'] = 0;

}

if(!isset($_SESSION['offsetInvoices'])){

    $_SESSION['offsetInvoices'] = 0;

}

$research = isset($_GET['research']) ? urlencode($_GET['research']) : "";
$filter = isset($_GET['filter']) ? urlencode($_GET['filter']) : "";
$sort = isset($_GET['sort']) ? urlencode($_GET['sort']) : "";

$offset = $_SESSION['offsetUsers'];

if(isset($_GET['research']) || isset($_GET['filter']) || isset($_GET['sort'])){

    $dataUsers = @file_get_contents("http://localhost:8081/showUsersPersonalizedData?research=$research&filter=$filter&sort=$sort&offset=$offset");

}else{

    $dataUsers = @file_get_contents("http://localhost:8081/showUsersDefaultData?offset=$offset");

}

if($dataUsers){

    $users = json_decode($dataUsers, true);
    $_SESSION['listUsers'] = array();

    if(is_array($users)){
        foreach($users as $user){
            $_SESSION['listUsers'][] = [
                'ID_USER' => $user['ID_USER'],
                'username' => $user['username'],
                'name' => $user['name'],
                'surname' => $user['surname'],
                'description' => $user['description'],
                'keyWord1' => $user['keyWord1'],
                'keyWord2' => $user['keyWord2'],
                'keyWord3' => $user['keyWord3'],
                'email' => $user['email'],
                'city' => $user['city'],
                'street' => $user['street'],
                'nb_street' => $user['nb_street'],
                'postal_code' => $user['postal_code'],
                'status' => $user['status'],
                'connected' => $user['connected'],
                'date_inscription' => $user['date_inscription'],
                'last_connection' => $user['lastConnection'],
                'birth_date' => $user['birthDate'],
                'banned' => $user['banned']
            ];
        }
    }
}

$researchAdvices = isset($_GET['researchAdvices']) ? urlencode($_GET['researchAdvices']) : "";
$filterAdvices = isset($_GET['filterAdvices']) ? urlencode($_GET['filterAdvices']) : "";
$sortAdvices = isset($_GET['sortAdvices']) ? urlencode($_GET['sortAdvices']) : "";

$offsetAdvices = $_SESSION['offsetAdvices'];

if(isset($_GET['researchAdvices']) || isset($_GET['filterAdvices']) || isset($_GET['sortAdvices'])){

    $response = file_get_contents("http://localhost:8081/showAdvicesPersonalizedData?research=$researchAdvices&filter=$filterAdvices&sort=$sortAdvices&offset=$offsetAdvices");

}else{

    $response = file_get_contents("http://localhost:8081/showAdvicesDefaultData?offset=$offsetAdvices");

}

$distinctThemes = [];

if($response){

    $decodedResponse = json_decode($response, true);
    $_SESSION['listAdvices'] = array();

    if(isset($decodedResponse['advices']) && is_array($decodedResponse['advices'])){
        foreach($decodedResponse['advices'] as $advice){
            $_SESSION['listAdvices'][] = [
                'ID_ADVICE' => $advice['ID_ADVICE'],
                'title' => $advice['title'],
                'theme' => $advice['theme'],
                'description' => $advice['description'],
                'date_publication' => $advice['date_publication'],
                'author' => $advice['auteur']
            ];
        }
    }

    if(isset($decodedResponse['themes']) && is_array($decodedResponse['themes'])){
        $distinctThemes = $decodedResponse['themes'];
    }

}

$researchEvents = isset($_GET['researchEvents']) ? urlencode($_GET['researchEvents']) : "";
$filterEvents = isset($_GET['filterEvents']) ? urlencode($_GET['filterEvents']) : "";
$sortEvents = isset($_GET['sortEvents']) ? urlencode($_GET['sortEvents']) : "";

$offsetEvents = $_SESSION['offsetEvents'];

if(isset($_GET['researchEvents']) || isset($_GET['filterEvents']) || isset($_GET['sortEvents'])){

    $response = file_get_contents("http://localhost:8081/showEventsPersonalizedData?research=$researchEvents&filter=$filterEvents&sort=$sortEvents&offset=$offsetEvents");

}else{

    $response = file_get_contents("http://localhost:8081/showEventsDefaultData?offset=$offsetEvents");

}

$distinctTypes = [];

if($response){

    $decodedResponse = json_decode($response, true);
    $_SESSION['listEvents'] = array();

    if(isset($decodedResponse['events']) && is_array($decodedResponse['events'])){
        foreach($decodedResponse['events'] as $event){
            $_SESSION['listEvents'][] = [
                'ID_EVENT' => $event['ID_EVENT'],
                'type' => $event['type'],
                'name' => $event['name'],
                'date_start' => $event['date_start'],
                'date_end' => $event['date_end'],
                'description' => $event['description'],
                'price' => $event['price'],
                'capacity' => $event['capacity'],
                'nb_inscription' => $event['nb_inscription'],
                'city' => $event['city'],
                'street' => $event['street'],
                'nb_street' => $event['nb_street'],
                'postal_code' => $event['postal_code'],
                'username' => $event['username'],
                'ID_WORK_ADDRESS' => $event['ID_WORK_ADDRESS']
            ];
        }
    }

    if(isset($decodedResponse['types']) && is_array($decodedResponse['types'])){
        $distinctTypes = $decodedResponse['types'];
    }

}

$researchShop = isset($_GET['researchShop']) ? urlencode($_GET['researchShop']) : "";
$filterShop = isset($_GET['filterShop']) ? urlencode($_GET['filterShop']) : "";
$sortShop = isset($_GET['sortShop']) ? urlencode($_GET['sortShop']) : "";

$offsetShop = $_SESSION['offsetShop'];

if(isset($_GET['researchShop']) || isset($_GET['filterShop']) || isset($_GET['sortShop'])){

    $response = file_get_contents("http://localhost:8081/showProductsPersonalizedData?research=$researchShop&filter=$filterShop&sort=$sortShop&offset=$offsetShop");

}else{

    $response = file_get_contents("http://localhost:8081/showProductsDefaultData?offset=$offsetShop");

}

$distinctTypesShop = [];

if($response){

    $decodedResponse = json_decode($response, true);
    $_SESSION['listShop'] = array();

    if(isset($decodedResponse['products']) && is_array($decodedResponse['products'])){
        foreach($decodedResponse['products'] as $product){
            $_SESSION['listShop'][] = [
                'ID_PRODUCT' => $product['ID_PRODUCT'],
                'name' => $product['name'],
                'type' => $product['type'],
                'description' => $product['description'],
                'price' => $product['price']
            ];
        }
    }

    if(isset($decodedResponse['types']) && is_array($decodedResponse['types'])){
        $distinctTypesShop = $decodedResponse['types'];
    }

}

$researchServices = isset($_GET['researchServices']) ? urlencode($_GET['researchServices']) : "";
$filterServices = isset($_GET['filterServices']) ? urlencode($_GET['filterServices']) : "";
$sortServices = isset($_GET['sortServices']) ? urlencode($_GET['sortServices']) : "";

$filterServiceProvider = isset($_GET['filterServiceProvider']) ? urlencode($_GET['filterServiceProvider']) : "";

$offsetServices = $_SESSION['offsetServices'];
$offsetServiceProviders = $_SESSION['offsetServiceProviders'];

if(isset($_GET['researchServices']) || isset($_GET['filterServices']) || isset($_GET['sortServices'])){

    $response = file_get_contents("http://localhost:8081/showServicesPersonalizedData?research=$researchServices&filter=$filterServices&sort=$sortServices&offset=$offsetServices");

}else{

    $response = file_get_contents("http://localhost:8081/showServicesDefaultData?offset=$offsetServices");

}

if(isset($_GET['filterServiceProvider'])){

    $response2 = file_get_contents("http://localhost:8081/showServiceProvidersPersonalizedData?filter=$filterServiceProvider&offset=$offsetServiceProviders");

}else{

    $response2 = file_get_contents("http://localhost:8081/showServiceProvidersDefaultData?offset=$offsetServiceProviders");

}

$distinctTypesServices = [];

if($response){

    $decodedResponse = json_decode($response, true);
    $_SESSION['listServices'] = array();

    if(isset($decodedResponse['services']) && is_array($decodedResponse['services'])){
        foreach($decodedResponse['services'] as $service){
            $_SESSION['listServices'][] = [
                'ID_SERVICE' => $service['ID_SERVICE'],
                'type' => $service['type'],
                'formation' => $service['formation'],
                'place' => $service['place'],
                'cost' => $service['cost'],
                'is_medical_confidential' => $service['is_medical_confidential'],
                'nb' => $service['nb']
            ];
        }
    }

    if(isset($decodedResponse['types']) && is_array($decodedResponse['types'])){
        $distinctTypesServices = $decodedResponse['types'];
    }

}

if($response2){

    $decodedResponse2 = json_decode($response2, true);
    $_SESSION['listServiceProvider'] = array();

    if(is_array($decodedResponse2)){
        foreach($decodedResponse2 as $service){
            $_SESSION['listServiceProvider'][] = [
                'email' => $service['email'],
                'ID_SERVICE_PROVIDER' => $service['ID_SERVICE_PROVIDER'],
                'type' => $service['type'],
                'username' => $service['username']
            ];
        }
    }
}

$offsetMessages = $_SESSION['offsetMessages'];

$dataMessages = @file_get_contents("http://localhost:8081/showMessagesDefaultData?offset=$offsetMessages");

if($dataMessages){

    $decodedResponse = json_decode($dataMessages, true);
    $_SESSION['listMessages'] = array();

    if(isset($decodedResponse['messages']) && is_array($decodedResponse['messages'])){
        foreach($decodedResponse['messages'] as $message){
            $_SESSION['listMessages'][] = [
                'ID_MESSAGE' => $message['ID_MESSAGE'],
                'date' => $message['date'],
                'content' => $message['content'],
                'sender' => $message['sender'],
                'suspected_status' => $message['suspected_status'],
            ];
        }
    }
}

$researchRequests = isset($_GET['researchRequests']) ? urlencode($_GET['researchRequests']) : "";
$filterRequests = isset($_GET['filterRequests']) ? urlencode($_GET['filterRequests']) : "";
$sortRequests = isset($_GET['sortRequests']) ? urlencode($_GET['sortRequests']) : "";

$offsetRequests = $_SESSION['offsetRequests'];

if (!isset($_SESSION['listRequests'])) {
    $_SESSION['listRequests'] = [];
}

if(isset($_GET['researchRequests']) || isset($_GET['filterRequests']) || isset($_GET['sortRequests'])){

    $dataRequests = @file_get_contents("http://localhost:8081/showRequestsPersonalizedData?research=$researchRequests&filter=$filterRequests&sort=$sortRequests&offset=$offsetRequests");

}else{

    $dataRequests = @file_get_contents("http://localhost:8081/showRequestsDefaultData?offset=$offsetRequests");

}

if($dataRequests){

    $decodedResponse = json_decode($dataRequests, true);
    $_SESSION['listRequests'] = array();

    $distinctSubjects = $decodedResponse['subjects'] ?? [];

    $requests = $decodedResponse['requests'] ?? [];

    if(is_array($requests)){
        foreach($requests as $request){
            $_SESSION['listRequests'][] = [
                'ID_REQUEST' => $request['ID_REQUEST'],
                'date' => $request['date'],
                'subject' => $request['subject'],
                'email' => $request['email'],
                'request' => $request['request']
            ];
        }
    }
}

$researchNotifs = isset($_GET['researchNotifs']) ? urlencode($_GET['researchNotifs']) : "";
$filterNotifs = isset($_GET['filterNotifs']) ? urlencode($_GET['filterNotifs']) : "";
$sortNotifs = isset($_GET['sortNotifs']) ? urlencode($_GET['sortNotifs']) : "";

$offsetNotifs = $_SESSION['offsetNotifs'];

if(isset($_GET['researchNotifs']) || isset($_GET['filterNotifs']) || isset($_GET['sortNotifs'])){

    $response = file_get_contents("http://localhost:8081/showNotificationsPersonalizedData?research=$researchNotifs&filter=$filterNotifs&sort=$sortNotifs&offset=$offsetNotifs");

}else{

    $response = file_get_contents("http://localhost:8081/showNotificationsDefaultData?offset=$offsetNotifs");

}

$distinctTypesNotifs = [];

if($response){

    $decodedResponse = json_decode($response, true);
    $_SESSION['listNotifs'] = array();

    if(isset($decodedResponse['notifs']) && is_array($decodedResponse['notifs'])){
        foreach($decodedResponse['notifs'] as $advice){
            $_SESSION['listNotifs'][] = [
                'ID_NOTIFICATION' => $advice['ID_NOTIFICATION'],
                'title' => $advice['title'],
                'description' => $advice['description'],
                'type' => $advice['type'],
                'username' => $advice['username'],
                'ID_CONSUMER' => $advice['ID_CONSUMER']
            ];
        }
    }

    if(isset($decodedResponse['types']) && is_array($decodedResponse['types'])){
        $distinctTypesNotifs = $decodedResponse['types'];
    }

}

$researchInvoices = isset($_GET['researchInvoices']) ? urlencode($_GET['researchInvoices']) : "";
$sortInvoices = isset($_GET['sortInvoices']) ? urlencode($_GET['sortInvoices']) : "";

$offsetInvoices = $_SESSION['offsetInvoices'];

if(isset($_GET['researchInvoices']) || isset($_GET['filterInvoices']) || isset($_GET['sortInvoices'])){

    $response = file_get_contents("http://localhost:8081/showInvoicesPersonalizedData?research=$researchInvoices&sort=$sortInvoices&offset=$offsetInvoices");

}else{

    $response = file_get_contents("http://localhost:8081/showInvoicesDefaultData?offset=$offsetInvoices");

}

if($response){

    $decodedResponse = json_decode($response, true);
    $_SESSION['listInvoices'] = array();

    if(isset($decodedResponse['invoices']) && is_array($decodedResponse['invoices'])){
        foreach($decodedResponse['invoices'] as $invoice){
            $_SESSION['listInvoices'][] = [
                'ID_INVOICE' => $invoice['ID_INVOICE'],
                'amount' => $invoice['amount'],
                'nb_services_provided' => $invoice['nb_services_provided'],
                'month_billed' => $invoice['month_billed'],
                'year_billed' => $invoice['year_billed'],
                'pdf_path' => $invoice['pdf_path'],
                'service_provider' => $invoice['service_provider']
            ];
        }
    }
}

$notifUsers = [

    "update_success" => "Mise à jour des informations réussie.",
    "ban_success" => "Bannissement mis à jour.",
    "delete_success" => "Suppression du compte réussi.",
    "email_sent" => "Envoi du mail réussi.",
    "delete_success" => "Suppression réussie.",
    "add_success" => "Ajout réussi.",
    "update_success" => "Mise à jour réussie.",

];

$notifUsersKey = $_GET["notif"] ?? null;

$successUsersMessage = $notifUsers[$notifUsersKey] ?? null;

$errorUsers = [

    "update_error" => "Mise à jour des informations échouée.",
    "ban_error" => "Échec de la mise à jour du bannissement.",
    "delete_error" => "Suppression du compte échouée.",
    "email_error" => "Envoi du mail échoué.",
    "delete_error" => "Suppression échouée.",
    "add_error" => "Ajout échoué.",
    "update_error" => "Mise à jour échouée.",

];

$errorUsersKey = $_GET["error"] ?? null;

$errorUsersMessage = $errorUsers[$errorUsersKey] ?? null;

?>
<body onload="loadWelcome()">
    <header>
        <img src="../medias/logos/logoEcriture.png" alt="logo Silver Happy" height="100px">
    </header>
    <main>
        <div class="nav pt-5">
            <ul>
                <li><a href="#pagewelcome" onclick="showWelcome()">Accueil</a></li>

                <form id="connForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="conn">
                </form>

                <form id="userForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="users">
                </form>
                <li><a href="#pageusers" onclick="hideWelcome(); document.getElementById('userForm').submit(); return false;">Gestion des utilisateurs</a></li>

                <form id="serviceForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="services">
                </form>
                <li><a href="#pageservices" onclick="hideWelcome(); document.getElementById('serviceForm').submit(); return false;">Gestion des services</a></li>

                <form id="shopForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="shop">
                </form>
                <li><a href="#pageshop" onclick="hideWelcome(); document.getElementById('shopForm').submit(); return false;">Gestion de la boutique</a></li>

                <form id="eventsForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="events">
                </form>
                <li><a href="#pageevents" onclick="hideWelcome(); document.getElementById('eventsForm').submit(); return false;">Gestion des événements</a></li>

                <form id="moneyForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="money">
                </form>
                <li><a href="#pagemoney" onclick="hideWelcome(); document.getElementById('moneyForm').submit(); return false;">Gestion financière</a></li>

                <form id="tipsForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="tips">
                </form>
                <li><a href="#pagetips" onclick="hideWelcome(); document.getElementById('tipsForm').submit(); return false;">Gestion des conseils</a></li>

                <form id="notifsForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="notifs">
                </form>
                <li><a href="#pagenotifs" onclick="hideWelcome(); document.getElementById('notifsForm').submit(); return false;">Gestion des notifications</a></li>

                <form id="messagesForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="messages">
                </form>
                <li><a href="#pagemessages" onclick="hideWelcome(); document.getElementById('messagesForm').submit(); return false;">Contrôle des messages</a></li>

                <form id="requestsForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="requests">
                </form>
                <li><a href="#pagerequests" onclick="hideWelcome(); document.getElementById('requestsForm').submit(); return false;">Contrôle des requêtes</a></li>


                <li><a href="deconnexion.php">Se déconnecter</a></li>
            </ul>
        </div>

        <div class="right">
            <div id="pagewelcome">
                <h1>Back Office Silver Happy</h1>
                <p>Utilisez la navigation à gauche pour accéder aux outils d'administration</p>
                </div>

            <section id="pageusers" class="mt-5">
                <?php if(isset($_SESSION['listUsers'])): ?>

                    <?php $connectedUser = 0; ?>

                    <?php foreach($_SESSION['listUsers'] as $user){

                        if($user['connected'] == 1){

                            $connectedUser += 1;

                        }

                        if(!isset($_SESSION['tableChoice'])){ $_SESSION['tableChoice'] = 0;}

                    } ?>

                <div class="row">
                    <div class="col-5">
                        <h1>Gestion des utilisateurs</h1>
                    </div>
                    <div class="col-2 pt-3">
                        <p>(<?php echo $connectedUser ?> utilisateur.s connecté.s)</p>
                    </div>
                </div>

                <div class="col-4">
                    <?php if (isset($errorUsersMessage)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($errorUsersMessage); ?>
                        </div>
                    <?php elseif(isset($successUsersMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($successUsersMessage); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <form method="GET" action="index.php#pageusers">
                    <div class="row">
                        <div class="col-2">
                            <div class="input-group">
                                <input value="<?php if(isset($_GET['research'])){ echo htmlspecialchars($_GET['research']); }else{ echo ""; } ?>" class="form-control" name="research" placeholder="<?php if(isset($_GET['research']) && $_GET['research'] != ""){ echo $_GET['research']; }else{ ?><?php echo "Tapez votre recherche"; ?> <?php } ?>" aria-label="Search">
                                <button class="searchButton" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="col-2">
                            <select name="filter" class="selectFilter" onchange="this.form.submit()">
                                <option disabled <?php if(!isset($_GET['filter']) || $_GET['filter'] == ""){echo 'selected';} ?>>Choisissez un filtre</option>
                                <option value="1" <?php if(isset($_GET['filter']) && $_GET['filter'] == "1"){echo 'selected';} ?>>Adhérents uniquement</option>
                                <option value="2" <?php if(isset($_GET['filter']) && $_GET['filter'] == "2"){echo 'selected';} ?>>Prestataires uniquement</option>
                                <option value="5" <?php if(isset($_GET['filter']) && $_GET['filter'] == "5"){echo 'selected';} ?>>Prestataires à valider</option>
                                <option value="3" <?php if(isset($_GET['filter']) && $_GET['filter'] == "3"){echo 'selected';} ?>>Connectés uniquement</option>
                                <option value="4" <?php if(isset($_GET['filter']) && $_GET['filter'] == "4"){echo 'selected';} ?>>Déconnectés uniquement</option>
                            </select>
                        </div>

                        <div class="col-2">
                            <select name="sort" class="selectSort" onchange="this.form.submit()">
                                <option disabled <?php if(!isset($_GET['sort']) || $_GET['sort'] == ""){echo 'selected';} ?>>Choisissez un tri</option>
                                <option value="1" <?php if(isset($_GET['sort']) && $_GET['sort'] == "1"){echo 'selected';} ?>>Plus anciens comptes en premier</option>
                                <option value="2" <?php if(isset($_GET['sort']) && $_GET['sort'] == "2"){echo 'selected';} ?>>Plus récents comptes en premier</option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="col-2 pt-3">
                    <?php if($_SESSION['tableChoice'] == 0){ ?>

                        <form method="POST" action="">
                            <input type="hidden" name="tableChoice" value="1">
                            <button>Affichage expert</button>
                        </form>

                    <?php }else{ ?>

                        <form method="POST" action="">
                            <input type="hidden" name="tableChoice" value="0">
                            <button>Affichage standart</button>
                        </form>

                    <?php } ?>
                </div>

                <br>

                    <?php if($_SESSION['tableChoice'] == 0){ ?>
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom d'utilisateur</th>
                                    <th scope="col">Prénom</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Date de naissance</th>
                                    <th scope="col">Ville</th>
                                    <th scope="col">Rue</th>
                                    <th scope="col">Numéro de rue</th>
                                    <th scope="col">Code Postal</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date d'inscription</th>
                                    <th scope="col">Connexion</th>
                                    <th scope="col">Bannissement</th>
                                    <th scope="col">Sélectionner</th>
                                    <th scope="col">Modifier</th>
                                    <th scope="col">Bannir</th>
                                    <th scope="col">Supprimer</th>
                                    <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach($_SESSION['listUsers'] as $user){ 

                                            $idForm = "form_" . $user['ID_USER'];
                                    ?>
                                        <tr>
                                            <th scope="row"> <?= htmlspecialchars($user['ID_USER']) ?></th>

                                            <td><input class="form-control" form="<?= $idForm ?>" name="username" class="mediumtext" type="text" value="<?= htmlspecialchars($user['username'] ?? '') ?>"></td>
                                                
                                            <td><input class="form-control" form="<?= $idForm ?>" name="name" class="mediumtext" type="text" value="<?= htmlspecialchars($user['name'] ?? '') ?>"></td>
                                            <td><input class="form-control" form="<?= $idForm ?>" name="surname" class="mediumtext" type="text" value="<?= htmlspecialchars($user['surname'] ?? '') ?>"></td>
                                            <td><input class="form-control" form="<?= $idForm ?>" name="email" class="mediumtext" type="text" value="<?= htmlspecialchars($user['email'] ?? '') ?>"></td>
                                            <td><input class="form-control" form="<?= $idForm ?>" name="birth_date" class="mediumtext" type="date" value="<?php if($user['birth_date'] == '1900-01-01'){echo "non renseigné";}else{echo  htmlspecialchars($user['birth_date']);} ?>"></td>
                                                
                                            <td><input class="form-control" form="<?= $idForm ?>" name="city" class="mediumtext" type="text" value="<?= htmlspecialchars($user['city'] ?? '') ?>"></td>
                                            <td><input class="form-control" form="<?= $idForm ?>" name="street" class="mediumtext" type="text" value="<?= htmlspecialchars($user['street'] ?? '') ?>"></td>
                                            <td><input class="form-control" form="<?= $idForm ?>" name="nb_street" class="mediumtext" type="text" value="<?= htmlspecialchars($user['nb_street'] ?? '') ?>"></td>
                                            <td><input class="form-control" form="<?= $idForm ?>" name="postal_code" class="mediumtext" type="text" value="<?= htmlspecialchars($user['postal_code'] ?? '') ?>"></td>

                                            <td><input class="form-control" form="<?= $idForm ?>" name="status" class="mediumtext" type="text" value="<?= htmlspecialchars($user['status'] ?? '') ?>"></td>
                                            <td><input class="form-control" form="<?= $idForm ?>" name="date_inscription" class="mediumtext" type="date" value="<?= htmlspecialchars($user['date_inscription'] ?? '0') ?>"></td>
                                            <td><?php if($user['connected'] == 1){echo "en cours";}else{echo date("d/m/Y", strtotime($user['last_connection']));} ?></td>
                                            <td><?= htmlspecialchars($user['banned'] ?? '') ?></td>

                                            <td>
                                                <div class="form-check">
                                                    <input form="selectedEmail" name="selectedEmail[]" class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($user['email']); ?>">
                                                </div>
                                            </td>

                                            <td>
                                                <form id="<?= $idForm ?>" method="POST" action="http://localhost:8081/updateUsersData">
                                                    <button type="submit" value="<?= $user['ID_USER'] ?>">Modifier</button>
                                                    <input type="hidden" name="id" value="<?= $user['ID_USER'] ?>">
                                                    <input type="hidden" name="description" value="<?= htmlspecialchars($user['description']) ?>">
                                                    <input type="hidden" name="keyWord1" value="<?= htmlspecialchars($user['keyWord1']) ?>">
                                                    <input type="hidden" name="keyWord2" value="<?= htmlspecialchars($user['keyWord2']) ?>">
                                                    <input type="hidden" name="keyWord3" value="<?= htmlspecialchars($user['keyWord3']) ?>">
                                                </form>
                                            </td>

                                            <td>
                                                <form method="POST" action="http://localhost:8081/banUser">
                                                    <button type="submit" value="<?= $user['ID_USER'] ?>"><?php if($user['banned'] == 0){ echo "Bannir"; }else{ echo "Pardonner"; } ?></button>
                                                    <input type="hidden" name="id" value="<?= $user['ID_USER'] ?>">
                                                    <input type="hidden" name="banned" value="<?= $user['banned'] ?>">
                                                </form>
                                            </td>

                                            <td>
                                                <form method="POST" action="http://localhost:8081/deleteUser">
                                                    <button type="submit" value="<?= $user['ID_USER'] ?>">Supprimer</button>
                                                    <input type="hidden" name="id" value="<?= $user['ID_USER'] ?>">
                                                    <input type="hidden" name="status" value="<?= $user['status'] ?>">
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                    <?php }elseif($_SESSION['tableChoice'] == 1){ ?>
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nom d'utilisateur</th>
                                <th scope="col">Prénom</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Email</th>
                                <th scope="col">Date de naissance</th>
                                <th scope="col">Ville</th>
                                <th scope="col">Rue</th>
                                <th scope="col">Numéro de rue</th>
                                <th scope="col">Code Postal</th>
                                <th scope="col">Description</th>
                                <th scope="col">Key word 1</th>
                                <th scope="col">Key word 2</th>
                                <th scope="col">Key word 3</th>
                                <th scope="col">Status</th>
                                <th scope="col">Casier judiciaire</th>
                                <th scope="col">Date d'inscription</th>
                                <th scope="col">Connexion</th>
                                <th scope="col">Bannissement</th>
                                <th scope="col">Sélectionner</th>
                                <th scope="col">Modifier</th>
                                <th scope="col">Bannir</th>
                                <th scope="col">Supprimer</th>
                                <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                    foreach($_SESSION['listUsers'] as $user){ 

                                        $idForm = "form_" . $user['ID_USER'];
                                    
                                    ?>
                                    <tr>
                                        <th scope="row"> <?= htmlspecialchars($user['ID_USER']) ?></th>

                                        <td><input class="form-control" form="<?= $idForm ?>" name="username" class="mediumtext" type="text" value="<?= htmlspecialchars($user['username'] ?? '') ?>"></td>
                                            
                                        <td><input class="form-control" form="<?= $idForm ?>" name="name" class="mediumtext" type="text" value="<?= htmlspecialchars($user['name'] ?? '') ?>"></td>
                                        <td><input class="form-control" form="<?= $idForm ?>" name="surname" class="mediumtext" type="text" value="<?= htmlspecialchars($user['surname'] ?? '') ?>"></td>
                                        <td><input class="form-control" form="<?= $idForm ?>" name="email" class="mediumtext" type="text" value="<?= htmlspecialchars($user['email'] ?? '') ?>"></td>
                                        <td><input class="form-control" form="<?= $idForm ?>" name="birth_date" class="mediumtext" type="date" value="<?php if($user['birth_date'] == '1900-01-01'){echo "non renseigné";}else{echo  htmlspecialchars($user['birth_date']);} ?>"></td>                                                                                            
                                        <td><input class="form-control" form="<?= $idForm ?>" name="city" class="mediumtext" type="text" value="<?= htmlspecialchars($user['city'] ?? '') ?>"></td>
                                        <td><input class="form-control" form="<?= $idForm ?>" name="street" class="mediumtext" type="text" value="<?= htmlspecialchars($user['street'] ?? '') ?>"></td>
                                        <td><input class="form-control" form="<?= $idForm ?>" name="nb_street" class="mediumtext" type="text" value="<?= htmlspecialchars($user['nb_street'] ?? '') ?>"></td>
                                        <td><input class="form-control" form="<?= $idForm ?>" name="postal_code" class="mediumtext" type="text" value="<?= htmlspecialchars($user['postal_code'] ?? '') ?>"></td>

                                        <td><input class="form-control" form="<?= $idForm ?>" name="description" class="bigtext" type="text" value="<?= htmlspecialchars($user['description'] ?? '') ?>"></td>
                                            
                                        <td><input class="form-control" form="<?= $idForm ?>" name="keyWord1" class="bigtext" type="text" value="<?= htmlspecialchars($user['keyWord1'] ?? '') ?>"></td>
                                        <td><input class="form-control" form="<?= $idForm ?>" name="keyWord2" class="bigtext" type="text" value="<?= htmlspecialchars($user['keyWord2'] ?? '') ?>"></td>
                                        <td><input class="form-control" form="<?= $idForm ?>" name="keyWord3" class="bigtext" type="text" value="<?= htmlspecialchars($user['keyWord3'] ?? '') ?>"></td>

                                        <td><input class="form-control" form="<?= $idForm ?>" name="status" class="bigtext" type="text" value="<?= htmlspecialchars($user['status'] ?? '0') ?>"></td>

                                        <td>
                                            <?php if($user['status'] == -2 || $user['status'] == 3 || $user['status'] == 4){ $criminalRecordPath = "../data/documents/criminal_record_" . htmlspecialchars($user['username']) . ".pdf"; ?>
                                            <a href="<?php echo $criminalRecordPath ?>" target="_blank"><button>PDF</button></a>
                                            <?php }else{ echo ""; } ?>
                                        </td>

                                        <td><input class="form-control" form="<?= $idForm ?>" name="date_inscription" class="mediumtext" type="date" value="<?= htmlspecialchars($user['date_inscription'] ?? '0') ?>"></td>
                                        <td><?php if($user['connected'] == 1){echo "en cours";}else{echo date("d/m/Y", strtotime($user['last_connection']));} ?></td>
                                        <td><?= htmlspecialchars($user['banned'] ?? '') ?></td>

                                        <td>
                                            <div class="form-check">
                                                <input form="selectedEmail" name="selectedEmail[]" class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($user['email']); ?>">
                                            </div>
                                        </td>

                                        <td>
                                            <form id="<?= $idForm ?>" method="POST" action="http://localhost:8081/updateUsersData">
                                                <button type="submit" value="<?= $user['ID_USER'] ?>">Modifier</button>
                                                <input type="hidden" name="id" value="<?= $user['ID_USER'] ?>">
                                            </form>
                                        </td>

                                        <td>
                                            <form method="POST" action="http://localhost:8081/banUser">
                                                <button type="submit" value="<?= $user['ID_USER'] ?>"><?php if($user['banned'] == 0){ echo "Bannir"; }else{ echo "Pardonner"; } ?></button>
                                                <input type="hidden" name="id" value="<?= $user['ID_USER'] ?>">
                                                <input type="hidden" name="banned" value="<?= $user['banned'] ?>">
                                            </form>
                                        </td>

                                        <td>
                                            <form method="POST" action="http://localhost:8081/deleteUser">
                                                <button type="submit" value="<?= $user['ID_USER'] ?>">Supprimer</button>
                                                <input type="hidden" name="id" value="<?= $user['ID_USER'] ?>">
                                                <input type="hidden" name="status" value="<?= $user['status'] ?>">
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pageUsers" value="moins">Précedent</button>
                    <button type="submit" name="pageUsers" value="plus">Suivant</button>
                    <button type="submit" name="pageUsers">Rafraîchir</button>

                    <input type="hidden" name="research" value="<?php if(isset($_GET['research'])){ echo $_GET['research']; } ?>">
                    <input type="hidden" name="filter" value="<?php if(isset($_GET['filter'])){ echo $_GET['filter']; } ?>">
                    <input type="hidden" name="sort" value="<?php if(isset($_GET['sort'])){ echo $_GET['sort']; } ?>">
                </form>
                <?php endif; ?>



                <h5 class="pt-5">Contacter les emails sélectionnés</h5>

                <form id="selectedEmail" method="POST" action="sendEmailSelectedUsers.php">

                    <label>Objet</label>
                    <input type="text" name="subject" class="form-control mb-3" placeholder="Saisissez l'objet de l'email" required>

                    <label>Corps de l'email</label>
                    <textarea type="text" name="mail" class="form-control mb-3" placeholder="Rédigez l'email" required></textarea>

                    <button type="submit"class="mb-5">Envoyer</button>

                </form>
            </section>

            <section id="pageservices" class="mt-5">
                <?php if(isset($_SESSION['listServices'])): ?>
                <h1>Gestion des services</h1>

                <div class="col-4">
                    <?php if (isset($errorUsersMessage)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($errorUsersMessage); ?>
                        </div>
                    <?php elseif(isset($successUsersMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($successUsersMessage); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <form method="GET" action="index.php#pageservices">
                    <div class="row mb-3">
                        <div class="col-2">
                            <div class="input-group">
                                <input value="<?php if(isset($_GET['researchServices'])){ echo htmlspecialchars($_GET['researchServices']); }else{ echo ""; } ?>" class="form-control" name="researchServices" placeholder="<?php if(isset($_GET['researchServices']) && $_GET['researchServices'] != ""){ echo $_GET['researchServices']; }else{ ?><?php echo "Tapez votre recherche"; ?> <?php } ?>" aria-label="Search">
                                <button class="searchButton" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="col-2">
                            <select name="filterServices" class="selectFilter" onchange="this.form.submit()">
                                <option disabled selected><?php echo "Choisissez un filtre" ?></option>
                                <?php foreach($distinctTypesServices as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>" <?php if(isset($_GET['filterServices']) && $_GET['filterServices'] == $type){ echo 'selected'; } ?> ><?= htmlspecialchars($type) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-2">
                            <select name="sortServices" class="selectSort" onchange="this.form.submit()">
                                <option disabled <?php if(!isset($_GET['sortServices']) || $_GET['sortServices'] == ""){echo 'selected';} ?>>Choisissez un tri</option>
                                <option value="1" <?php if(isset($_GET['sortServices']) && $_GET['sortServices'] == "1"){echo 'selected';} ?>>Coût croissant</option>
                                <option value="2" <?php if(isset($_GET['sortServices']) && $_GET['sortServices'] == "2"){echo 'selected';} ?>>Coût décroissant</option>
                                <option value="3" <?php if(isset($_GET['sortServices']) && $_GET['sortServices'] == "3"){echo 'selected';} ?>>Nombre prestatires croissant</option>
                                <option value="4" <?php if(isset($_GET['sortServices']) && $_GET['sortServices'] == "4"){echo 'selected';} ?>>Nombre prestatires décroissant</option>
                            </select>
                        </div>
                    </div>
                </form>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Type</th>
                        <th scope="col">Formation</th>
                        <th scope="col">Place</th>
                        <th scope="col">Coût</th>
                        <th scope="col">Confidentiel ?</th>
                        <th scope="col">Nombre de prestataires</th>
                        <th scope="col">Sélectionner</th>
                        <th scope="col">Modifier</th>
                        <th scope="col">Supprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($_SESSION['listServices'] as $service){

                                $idFormService = "form_service_" . $service['ID_SERVICE'];
                        ?>
                                <tr>
                                    <th scope="row"> <?= htmlspecialchars($service['ID_SERVICE']) ?></th>

                                    <td><input class="form-control" form="<?= $idFormService ?>" name="type" class="mediumtext" type="text" value="<?= htmlspecialchars($service['type'] ?? '') ?>"></td>

                                    <td>
                                        <select class="form-control" form="<?= $idFormService ?>" name="formation"> 
                                            <option class="smalltext" value="0" <?= htmlspecialchars($service['formation'] ?? 0) == 0 ? 'selected' : '' ?>>Non</option>
                                            <option class="smalltext" value="1" <?= htmlspecialchars($service['formation'] ?? 0) == 1 ? 'selected' : '' ?>>Oui</option>
                                        </select>
                                    </td>

                                    <td><input class="form-control" form="<?= $idFormService ?>" name="place" class="bigtext" type="text" value="<?= htmlspecialchars($service['place'] ?? '') ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormService ?>" name="cost" class="smalltext" type="text" value="<?= htmlspecialchars($service['cost'] ?? '') ?>"></td>

                                    <td>
                                        <select class="form-control" form="<?= $idFormService ?>" name="is_medical_confidential"> 
                                            <option class="smalltext" value="0" <?= htmlspecialchars($service['is_medical_confidential'] ?? 0) == 0 ? 'selected' : '' ?>>Non</option>
                                            <option class="smalltext" value="1" <?= htmlspecialchars($service['is_medical_confidential'] ?? 0) == 1 ? 'selected' : '' ?>>Oui</option>
                                        </select>
                                    </td>
                                    
                                    <td><?= htmlspecialchars($service['nb'] ?? '') ?></td>

                                    <td>
                                        <div class="form-check">
                                            <input form="selectedService" name="selectedService" class="form-check-input" type="radio" value="<?php echo htmlspecialchars($service['ID_SERVICE']); ?>">
                                        </div>
                                    </td>

                                    <td>
                                        <form id="<?= $idFormService ?>" method="POST" action="http://localhost:8081/updateServiceData">
                                            <button type="submit" value="<?= $service['ID_SERVICE'] ?>">Modifier</button>
                                            <input type="hidden" name="id" value="<?= $service['ID_SERVICE'] ?>">
                                        </form>
                                    </td>

                                    <td>
                                        <form method="POST" action="http://localhost:8081/deleteService">
                                            <button type="submit" value="<?= $service['ID_SERVICE'] ?>">Supprimer</button>
                                            <input type="hidden" name="id" value="<?= $service['ID_SERVICE'] ?>">
                                        </form>
                                    </td>

                                </tr>

                            <?php } ?>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pageservices" value="moins">Précedent</button>
                    <button type="submit" name="pageservices" value="plus">Suivant</button>
                    <button type="submit" name="pageservices">Rafraîchir</button>

                    <input type="hidden" name="researchServices" value="<?php if(isset($_GET['researchServices'])){ echo $_GET['researchServices']; } ?>">
                    <input type="hidden" name="filterServices" value="<?php if(isset($_GET['filterServices'])){ echo $_GET['filterServices']; } ?>">
                    <input type="hidden" name="sortServices" value="<?php if(isset($_GET['sortServices'])){ echo $_GET['sortServices']; } ?>">
                </form>

                <form method="GET" action="index.php#pageservices">
                    <div class="row mb-3 pt-5 mt-4">
                        <div class="col-2">
                            <select name="filterServiceProvider" class="filterServiceProvider" onchange="this.form.submit()">
                                <option disabled selected><?php echo "Choisissez un filtre" ?></option>
                                <?php foreach($distinctTypesServices as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>" <?php if(isset($_GET['filterServiceProvider']) && $_GET['filterServiceProvider'] == $type){ echo 'selected'; } ?> ><?= htmlspecialchars($type) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </form>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">Type</th>
                        <th scope="col">Prestataire</th>
                        <th scope="col">Sélectionner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($_SESSION['listServiceProvider'] as $serviceProvider){

                            $idForm = "form_service_provider_" . $serviceProvider['ID_SERVICE_PROVIDER'];
                        ?>
                                <tr>
                                    <td><?= htmlspecialchars($serviceProvider['type'] ?? '') ?></td>

                                    
                                    <td><?= htmlspecialchars($serviceProvider['username'] ?? '') ?></td>

                                    <td>
                                        <div class="form-check">
                                            <input form="selectedServiceProviders" name="selectedServiceProviders[]" class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($serviceProvider['email']); ?>">
                                        </div>
                                    </td>

                                </tr>

                            <?php } ?>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pageserviceproviders" value="moins">Précedent</button>
                    <button type="submit" name="pageserviceproviders" value="plus">Suivant</button>
                    <button type="submit" name="pageserviceproviders">Rafraîchir</button>

                    <input type="hidden" name="filterServiceProvider" value="<?php if(isset($_GET['filterServiceProvider'])){ echo $_GET['filterServiceProvider']; } ?>">
                </form>

                <h5 class="pt-5">Ajouter un service</h5>

                <form method="POST" action="http://localhost:8081/addService">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">Type</th>
                            <th scope="col">Formation</th>
                            <th scope="col">Place</th>
                            <th scope="col">Coût</th>
                            <th scope="col">Confidentiel ?</th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="form-control mediumtext" type="text" name="type"></td>

                                <td>
                                    <select class="form-control" name="formation"> 
                                        <option class="smalltext" value="0">Non</option>
                                        <option class="smalltext" value="1">Oui</option>
                                    </select>
                                </td>

                                <td><input class="form-control bigtext" type="text" name="place"></td>

                                <td><input class="form-control smalltext" type="number" name="cost"></td>

                                <td>
                                    <select class="form-control" name="is_medical_confidential"> 
                                        <option class="smalltext" value="0">Non</option>
                                        <option class="smalltext" value="1">Oui</option>
                                    </select>
                                </td>
                                        
                                <td><button class="button" type="submit" name="addevent">Ajouter</button></td>
                            </tr>                   
                        </tbody>
                    </table>
                </form>

                <div class="row">
                    <div class="col-6">
                        <h5 class="pt-5">Contacter par email les prestataires concernés par le service sélectionné</h5>

                        <form id="selectedService" method="POST" action="sendEmailSelectedService.php">

                            <label>Objet</label>
                            <input type="text" name="subject" class="form-control mb-3" placeholder="Saisissez l'objet de l'email" required>

                            <label>Corps de l'email</label>
                            <textarea type="text" name="mail" class="form-control mb-3" placeholder="Rédigez l'email" required></textarea>

                            <button type="submit"class="mb-5">Envoyer</button>

                        </form>
                    </div>
                    <div class="col-6">
                        <h5 class="pt-5">Contacter par email les prestataires directement spécifiquement sélectionnés</h5>

                        <form id="selectedServiceProviders" method="POST" action="sendEmailSelectedServiceProviders.php">

                            <label>Objet</label>
                            <input type="text" name="subject" class="form-control mb-3" placeholder="Saisissez l'objet de l'email" required>

                            <label>Corps de l'email</label>
                            <textarea type="text" name="mail" class="form-control mb-3" placeholder="Rédigez l'email" required></textarea>

                            <button type="submit"class="mb-5">Envoyer</button>

                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </section>

            <section id="pageshop" class="mt-5">
                <?php if(isset($_SESSION['listShop'])): ?>
                <h1>Gestion de la boutique</h1>

                <div class="col-4">
                    <?php if (isset($errorUsersMessage)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($errorUsersMessage); ?>
                        </div>
                    <?php elseif(isset($successUsersMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($successUsersMessage); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <form method="GET" action="index.php#pageshop">
                    <div class="row mb-3">
                        <div class="col-2">
                            <div class="input-group">
                                <input value="<?php if(isset($_GET['researchShop'])){ echo htmlspecialchars($_GET['researchShop']); }else{ echo ""; } ?>" class="form-control" name="researchShop" placeholder="<?php if(isset($_GET['researchShop']) && $_GET['researchShop'] != ""){ echo $_GET['researchShop']; }else{ ?><?php echo "Tapez votre recherche"; ?> <?php } ?>" aria-label="Search">
                                <button class="searchButton" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="col-2">
                            <select name="filterShop" class="selectFilter" onchange="this.form.submit()">
                                <option disabled selected><?php echo "Choisissez un filtre" ?></option>
                                <?php foreach($distinctTypesShop as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>" <?php if(isset($_GET['filterShop']) && $_GET['filterShop'] == $type){ echo 'selected'; } ?> ><?= htmlspecialchars($type) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-2">
                            <select name="sortShop" class="selectSort" onchange="this.form.submit()">
                                <option disabled <?php if(!isset($_GET['sortShop']) || $_GET['sortShop'] == ""){echo 'selected';} ?>>Choisissez un tri</option>
                                <option value="1" <?php if(isset($_GET['sortShop']) && $_GET['sortShop'] == "1"){echo 'selected';} ?>>Prix croissant</option>
                                <option value="2" <?php if(isset($_GET['sortShop']) && $_GET['sortShop'] == "2"){echo 'selected';} ?>>Prix décroissant</option>
                            </select>
                        </div>
                    </div>
                </form>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Type</th>
                        <th scope="col">Description</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Sélectionner</th>
                        <th scope="col">Modifier</th>
                        <th scope="col">Supprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($_SESSION['listShop'] as $product){

                                $idFormProduct = "form_product_" . $product['ID_PRODUCT'];
                        ?>
                                <tr>
                                    <th scope="row"> <?= htmlspecialchars($product['ID_PRODUCT']) ?></th>

                                    <td><input class="form-control" form="<?= $idFormProduct ?>" name="name" class="mediumtext" type="text" value="<?= htmlspecialchars($product['name'] ?? '') ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormProduct ?>" name="type" class="mediumtext" type="text" value="<?= htmlspecialchars($product['type'] ?? '') ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormProduct ?>" name="description" class="bigtext" type="text" value="<?= htmlspecialchars($product['description'] ?? '') ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormProduct ?>" name="price" class="smalltext" type="text" value="<?= htmlspecialchars($product['price'] ?? '') ?>"></td>

                                    <td>
                                        <div class="form-check">
                                            <input form="selectedProduct" name="selectedProduct" class="form-check-input" type="radio" value="<?php echo htmlspecialchars($product['ID_PRODUCT']); ?>">
                                        </div>
                                    </td>

                                    <td>
                                        <form id="<?= $idFormProduct ?>" method="POST" action="http://localhost:8081/updateProductData">
                                            <button type="submit" value="<?= $product['ID_PRODUCT'] ?>">Modifier</button>
                                            <input type="hidden" name="id" value="<?= $product['ID_PRODUCT'] ?>">
                                        </form>
                                    </td>

                                    <td>
                                        <form method="POST" action="http://localhost:8081/deleteProduct">
                                            <button type="submit" value="<?= $product['ID_PRODUCT'] ?>">Supprimer</button>
                                            <input type="hidden" name="id" value="<?= $product['ID_PRODUCT'] ?>">
                                        </form>
                                    </td>

                                </tr>

                            <?php } ?>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pageshop" value="moins">Précedent</button>
                    <button type="submit" name="pageshop" value="plus">Suivant</button>
                    <button type="submit" name="pageshop">Rafraîchir</button>

                    <input type="hidden" name="researchShop" value="<?php if(isset($_GET['researchShop'])){ echo $_GET['researchShop']; } ?>">
                    <input type="hidden" name="filterShop" value="<?php if(isset($_GET['filterShop'])){ echo $_GET['filterShop']; } ?>">
                    <input type="hidden" name="sortShop" value="<?php if(isset($_GET['sortShop'])){ echo $_GET['sortShop']; } ?>">
                </form>

                <h5 class="pt-5">Ajouter un article</h5>

                <form method="POST" action="http://localhost:8081/addProduct">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Type</th>
                            <th scope="col">Description</th>
                            <th scope="col">Prix</th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="form-control mediumtext" type="text" name="name"></td>

                                <td><input class="form-control mediumtext" type="text" name="type"></td>

                                <td><input class="form-control bigtext" type="text" name="description"></td>

                                <td><input class="form-control smalltext" type="text" name="price"></td>
                                        
                                <td><button class="button" type="submit" name="addevent">Ajouter</button></td>
                            </tr>                   
                        </tbody>
                    </table>
                </form>

                <h5 class="pt-5">Contacter par email les utilisateurs concernés par l'article sélectionné</h5>

                <form id="selectedProduct" method="POST" action="sendEmailSelectedProduct.php">

                    <label>Objet</label>
                    <input type="text" name="subject" class="form-control mb-3" placeholder="Saisissez l'objet de l'email" required>

                    <label>Corps de l'email</label>
                    <textarea type="text" name="mail" class="form-control mb-3" placeholder="Rédigez l'email" required></textarea>

                    <button type="submit"class="mb-5">Envoyer</button>

                </form>
                <?php endif; ?>
            </section>

            <section id="pageevents" class="mt-5">
                <?php if(isset($_SESSION['listEvents'])): ?>
                <h1>Gestion des événements</h1>

                <div class="col-4">
                    <?php if (isset($errorUsersMessage)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($errorUsersMessage); ?>
                        </div>
                    <?php elseif(isset($successUsersMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($successUsersMessage); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <form method="GET" action="index.php#pageevents">
                    <div class="row mb-3">
                        <div class="col-2">
                            <div class="input-group">
                                <input value="<?php if(isset($_GET['researchEvents'])){ echo htmlspecialchars($_GET['researchEvents']); }else{ echo ""; } ?>" class="form-control" name="researchEvents" placeholder="<?php if(isset($_GET['researchEvents']) && $_GET['researchEvents'] != ""){ echo $_GET['researchEvents']; }else{ ?><?php echo "Tapez votre recherche"; ?> <?php } ?>" aria-label="Search">
                                <button class="searchButton" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="col-2">
                            <select name="filterEvents" class="selectFilter" onchange="this.form.submit()">
                                <option disabled selected><?php echo "Choisissez un filtre" ?></option>
                                <?php foreach($distinctTypes as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>" <?php if(isset($_GET['filterEvents']) && $_GET['filterEvents'] == $type){ echo 'selected'; } ?> ><?= htmlspecialchars($type) ?></option>
                                <?php endforeach; ?>
                                    <option value="perso1" <?php if(isset($_GET['filterEvents']) && $_GET['filterEvents'] == "perso1"){echo 'selected';} ?>>Terminés uniquement</option>
                                    <option value="perso2" <?php if(isset($_GET['filterEvents']) && $_GET['filterEvents'] == "perso2"){echo 'selected';} ?>>N'ayant pas commencés uniquement</option>
                            </select>
                        </div>

                        <div class="col-2">
                            <select name="sortEvents" class="selectSort" onchange="this.form.submit()">
                                <option disabled <?php if(!isset($_GET['sortEvents']) || $_GET['sortEvents'] == ""){echo 'selected';} ?>>Choisissez un tri</option>
                                <option value="1" <?php if(isset($_GET['sortEvents']) && $_GET['sortEvents'] == "1"){echo 'selected';} ?>>Date de début la plus proche</option>
                                <option value="2" <?php if(isset($_GET['sortEvents']) && $_GET['sortEvents'] == "2"){echo 'selected';} ?>>Date de début la plus tard</option>
                                <option value="5" <?php if(isset($_GET['sortEvents']) && $_GET['sortEvents'] == "5"){echo 'selected';} ?>>Terminés uniquement</option>
                                <option value="6" <?php if(isset($_GET['sortEvents']) && $_GET['sortEvents'] == "6"){echo 'selected';} ?>>N'ayant pas commencés uniquement</option>
                                <option value="3" <?php if(isset($_GET['sortEvents']) && $_GET['sortEvents'] == "3"){echo 'selected';} ?>>Haut niveau de participation</option>
                                <option value="4" <?php if(isset($_GET['sortEvents']) && $_GET['sortEvents'] == "4"){echo 'selected';} ?>>Bas niveau de participation</option>
                            </select>
                        </div>
                    </div>
                </form>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Type</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Date de début</th>
                        <th scope="col">Date de fin</th>
                        <th scope="col">Description</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Capacité</th>
                        <th scope="col">Nombre d'inscrits</th>
                        <th scope="col">Ville</th>
                        <th scope="col">Rue</th>
                        <th scope="col">Numéro de rue</th>
                        <th scope="col">Code postal</th>
                        <th scope="col">Organisateur</th>
                        <th scope="col">Sélectionner</th>
                        <th scope="col">Modifier</th>
                        <th scope="col">Supprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($_SESSION['listEvents'] as $event){

                                $idFormEvent = "form_event_" . $event['ID_EVENT'];
                        ?>
                                <tr>
                                    <th scope="row"> <?= htmlspecialchars($event['ID_EVENT']) ?></th>

                                    <td><input class="form-control" form="<?= $idFormEvent ?>" name="type" class="mediumtext" type="text" value="<?= htmlspecialchars($event['type'] ?? '') ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormEvent ?>" name="name" class="mediumtext" type="text" value="<?= htmlspecialchars($event['name'] ?? '') ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormEvent ?>" name="date_start" class="mediumtext" type="datetime-local" value="<?= !empty($event['date_start']) ? date('Y-m-d\TH:i', strtotime($event['date_start'])) : '' ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormEvent ?>" name="date_end" class="mediumtext" type="datetime-local" value="<?= !empty($event['date_end']) ? date('Y-m-d\TH:i', strtotime($event['date_end'])) : '' ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormEvent ?>" name="description" class="mediumtext" type="text" value="<?= htmlspecialchars($event['description'] ?? '') ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormEvent ?>" name="price" class="mediumtext" type="number" value="<?= htmlspecialchars($event['price'] ?? '') ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormEvent ?>" name="capacity" class="mediumtext" type="number" value="<?= htmlspecialchars($event['capacity'] ?? '') ?>"></td>

                                    <td><?= htmlspecialchars($event['nb_inscription'] ?? '') ?></td>

                                    <td><input class="form-control" form="<?= $idFormEvent ?>" name="city" class="mediumtext" type="text" value="<?= htmlspecialchars($event['city'] ?? '') ?>"></td>
                                    
                                    <td><input class="form-control" form="<?= $idFormEvent ?>" name="street" class="mediumtext" type="text" value="<?= htmlspecialchars($event['street'] ?? '') ?>"></td>
                                    
                                    <td><input class="form-control" form="<?= $idFormEvent ?>" name="nb_street" class="mediumtext" type="text" value="<?= htmlspecialchars($event['nb_street'] ?? '') ?>"></td>
                                    
                                    <td><input class="form-control" form="<?= $idFormEvent ?>" name="postal_code" class="mediumtext" type="text" value="<?= htmlspecialchars($event['postal_code'] ?? '') ?>"></td>

                                    <td><?= htmlspecialchars($event['username'] ?? '') ?></td>
                                        
                                    <td>
                                         <div class="form-check">
                                            <input form="selectedEvent" name="selectedEvent" class="form-check-input" type="radio" value="<?php echo htmlspecialchars($event['ID_EVENT']); ?>">
                                        </div>
                                    </td>

                                    <td>
                                        <form id="<?= $idFormEvent ?>" method="POST" action="http://localhost:8081/updateEventData">
                                            <button type="submit" value="<?= $event['ID_EVENT'] ?>">Modifier</button>
                                            <input type="hidden" name="id" value="<?= $event['ID_EVENT'] ?>">
                                            <input type="hidden" name="ID_WORK_ADDRESS" value="<?= $event['ID_WORK_ADDRESS'] ?>">
                                        </form>
                                    </td>

                                    <td>
                                        <form method="POST" action="http://localhost:8081/deleteEvent">
                                            <button type="submit" value="<?= $event['ID_EVENT'] ?>">Supprimer</button>
                                            <input type="hidden" name="id" value="<?= $event['ID_EVENT'] ?>">
                                        </form>
                                    </td>

                                </tr>

                            <?php } ?>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pageevents" value="moins">Précedent</button>
                    <button type="submit" name="pageevents" value="plus">Suivant</button>
                    <button type="submit" name="pageevents">Rafraîchir</button>

                    <input type="hidden" name="researchEvents" value="<?php if(isset($_GET['researchEvents'])){ echo $_GET['researchEvents']; } ?>">
                    <input type="hidden" name="filterEvents" value="<?php if(isset($_GET['filterEvents'])){ echo $_GET['filterEvents']; } ?>">
                    <input type="hidden" name="sortEvents" value="<?php if(isset($_GET['sortEvents'])){ echo $_GET['sortEvents']; } ?>">
                </form>

                <h5 class="pt-5">Ajouter un événement</h5>

                <form method="POST" action="http://localhost:8081/addEvent">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">Type</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Date de début</th>
                            <th scope="col">Date de fin</th>
                            <th scope="col">Description</th>
                            <th scope="col">Prix</th>
                            <th scope="col">Capacité</th>
                            <th scope="col">Ville</th>
                            <th scope="col">Rue</th>
                            <th scope="col">Numéro de rue</th>
                            <th scope="col">Code postal</th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="form-control mediumtext" type="text" name="type"></td>

                                <td><input class="form-control mediumtext" type="text" name="name"></td>

                                <td><input class="form-control mediumtext" type="datetime-local" name="date_start"></td>

                                <td><input class="form-control mediumtext" type="datetime-local" name="date_end"></td>

                                <td><input class="form-control bigtext" type="text" name="description"></td>

                                <td><input class="form-control bigtext" type="number" name="price"></td>

                                <td><input class="form-control bigtext" type="number" name="capacity"></td>

                                <td><input class="form-control bigtext" type="text" name="city"></td>

                                <td><input class="form-control bigtext" type="text" name="street"></td>

                                <td><input class="form-control bigtext" type="number" name="nb_street"></td>

                                <td><input class="form-control bigtext" type="text" name="postal_code"></td>
                                        
                                <td><button class="button" type="submit" name="addevent">Ajouter</button></td>
                            </tr>                   
                        </tbody>
                    </table>
                </form>

                <h5 class="pt-5">Contacter par emails les utilisateurs concernés par l'événement sélectionné</h5>

                <form id="selectedEvent" method="POST" action="sendEmailSelectedEvent.php">

                    <label>Objet</label>
                    <input type="text" name="subject" class="form-control mb-3" placeholder="Saisissez l'objet de l'email" required>

                    <label>Corps de l'email</label>
                    <textarea type="text" name="mail" class="form-control mb-3" placeholder="Rédigez l'email" required></textarea>

                    <button type="submit"class="mb-5">Envoyer</button>

                </form>
                <?php endif; ?>
            </section>

            <section id="pagemoney" class="mt-5">
                <?php if(isset($_SESSION['listInvoices'])): ?>
                <h1>Gestion Financière</h1>
                <br>
                <h1>Gestion des factures</h1>

                <div class="col-4">
                    <?php if (isset($errorUsersMessage)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($errorUsersMessage); ?>
                        </div>
                    <?php elseif(isset($successUsersMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($successUsersMessage); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <form method="GET" action="index.php#pagemoney">
                    <div class="row mb-3">
                        <div class="col-2">
                            <div class="input-group">
                                <input value="<?php if(isset($_GET['researchInvoices'])){ echo htmlspecialchars($_GET['researchInvoices']); }else{ echo ""; } ?>" class="form-control" name="researchInvoices" placeholder="<?php if(isset($_GET['researchInvoices']) && $_GET['researchInvoices'] != ""){ echo $_GET['researchInvoices']; }else{ ?><?php echo "Tapez votre recherche"; ?> <?php } ?>" aria-label="Search">
                                <button class="searchButton" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="col-2">
                            <select name="sortInvoices" class="selectSort" onchange="this.form.submit()">
                                <option disabled <?php if(!isset($_GET['sortInvoices']) || $_GET['sortInvoices'] == ""){echo 'selected';} ?>>Choisissez un tri</option>
                                <option value="1" <?php if(isset($_GET['sortInvoices']) && $_GET['sortInvoices'] == "1"){echo 'selected';} ?>>Plus anciens en premier</option>
                                <option value="2" <?php if(isset($_GET['sortInvoices']) && $_GET['sortInvoices'] == "2"){echo 'selected';} ?>>Plus récents en premier</option>
                                <option value="3" <?php if(isset($_GET['sortInvoices']) && $_GET['sortInvoices'] == "3"){echo 'selected';} ?>>Montant croissant</option>
                                <option value="4" <?php if(isset($_GET['sortInvoices']) && $_GET['sortInvoices'] == "4"){echo 'selected';} ?>>Montant décroissant</option>
                            </select>
                        </div>
                    </div>
                </form>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Montant</th>
                        <th scope="col">Nombre de services réalisés</th>
                        <th scope="col">Mois payé</th>
                        <th scope="col">Année payée</th>
                        <th scope="col">Lien PDF</th>
                        <th scope="col">Prestataire</th>
                        <th scope="col">Sélectionner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($_SESSION['listInvoices'] as $product){

                                $idFormInvoice = "form_invoice_" . $invoice['ID_INVOICE'];
                        ?>
                                <tr>
                                    <th scope="row"> <?= htmlspecialchars($invoice['ID_INVOICE']) ?></th>

                                    <td><?= htmlspecialchars($invoice['amount'] ?? '') ?></td>

                                    <td><?= htmlspecialchars($invoice['nb_services_provided'] ?? '') ?></td>

                                    <td><?= htmlspecialchars($invoice['month_billed'] ?? '') ?></td>

                                    <td><?= htmlspecialchars($invoice['year_billed'] ?? '') ?></td>

                                    <td><?= htmlspecialchars($invoice['pdf_path'] ?? '') ?></td>

                                    <td><?= htmlspecialchars($invoice['service_provider'] ?? '') ?></td>

                                    <td>
                                        <div class="form-check">
                                            <input form="selectedInvoice" name="selectedInvoice" class="form-check-input" type="radio" value="<?php echo htmlspecialchars($invoice['ID_INVOICE']); ?>">
                                        </div>
                                    </td>
                                </tr>

                            <?php } ?>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pagemoney" value="moins">Précedent</button>
                    <button type="submit" name="pagemoney" value="plus">Suivant</button>
                    <button type="submit" name="pagemoney">Rafraîchir</button>

                    <input type="hidden" name="researchInvoices" value="<?php if(isset($_GET['researchInvoices'])){ echo $_GET['researchInvoices']; } ?>">
                    <input type="hidden" name="sortInvoices" value="<?php if(isset($_GET['sortInvoices'])){ echo $_GET['sortInvoices']; } ?>">
                </form>

                <h5 class="pt-5">Contacter par email le prestataire concerné par la facture sélectionnée</h5>

                <form id="selectedInvoice" method="POST" action="sendEmailSelectedInvoice.php">

                    <label>Objet</label>
                    <input type="text" name="subject" class="form-control mb-3" placeholder="Saisissez l'objet de l'email" required>

                    <label>Corps de l'email</label>
                    <textarea type="text" name="mail" class="form-control mb-3" placeholder="Rédigez l'email" required></textarea>

                    <button type="submit"class="mb-5">Envoyer</button>

                </form>
                <?php endif; ?>
            </section>
            
            <section id="pagetips" class="mt-5">
                <?php if(isset($_SESSION['listAdvices'])): ?>
                <h1>Gestion des conseils</h1>

                <div class="col-4">
                    <?php if (isset($errorUsersMessage)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($errorUsersMessage); ?>
                        </div>
                    <?php elseif(isset($successUsersMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($successUsersMessage); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <form method="GET" action="index.php#pagetips">
                    <div class="row mb-3">
                        <div class="col-2">
                            <div class="input-group">
                                <input value="<?php if(isset($_GET['researchAdvices'])){ echo htmlspecialchars($_GET['researchAdvices']); }else{ echo ""; } ?>" class="form-control" name="researchAdvices" placeholder="<?php if(isset($_GET['researchAdvices']) && $_GET['researchAdvices'] != ""){ echo $_GET['researchAdvices']; }else{ ?><?php echo "Tapez votre recherche"; ?> <?php } ?>" aria-label="Search">
                                <button class="searchButton" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="col-2">
                            <select name="filterAdvices" class="selectFilter" onchange="this.form.submit()">
                                <option disabled selected><?php echo "Choisissez un thème" ?></option>
                                <?php foreach($distinctThemes as $theme): ?>
                                    <option value="<?= htmlspecialchars($theme) ?>" <?php if(isset($_GET['filterAdvices']) && $_GET['filterAdvices'] == $theme){ echo 'selected'; } ?> ><?= htmlspecialchars($theme) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-2">
                            <select name="sortAdvices" class="selectSort" onchange="this.form.submit()">
                                <option disabled <?php if(!isset($_GET['sortAdvices']) || $_GET['sortAdvices'] == ""){echo 'selected';} ?>>Choisissez un tri</option>
                                <option value="1" <?php if(isset($_GET['sortAdvices']) && $_GET['sortAdvices'] == "1"){echo 'selected';} ?>>Plus anciens en premier</option>
                                <option value="2" <?php if(isset($_GET['sortAdvices']) && $_GET['sortAdvices'] == "2"){echo 'selected';} ?>>Plus récents en premier</option>
                                <option value="3" <?php if(isset($_GET['sortAdvices']) && $_GET['sortAdvices'] == "3"){echo 'selected';} ?>>Plus enregistrés en premier</option>
                                <option value="4" <?php if(isset($_GET['sortAdvices']) && $_GET['sortAdvices'] == "4"){echo 'selected';} ?>>Moins enregistrés en premier</option>
                            </select>
                        </div>
                    </div>
                </form>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Thème</th>
                        <th scope="col">Description</th>
                        <th scope="col">Date de publication</th>
                        <th scope="col">Auteur</th>
                        <th scope="col">Sélectionner</th>
                        <th scope="col">Modifier</th>
                        <th scope="col">Supprimer</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($_SESSION['listAdvices'] as $advice){

                                $idFormAdvice = "form_advice_" . $advice['ID_ADVICE'];
                        ?>
                                <tr>
                                    <th scope="row"> <?= htmlspecialchars($advice['ID_ADVICE']) ?></th>

                                    <td><input class="form-control" form="<?= $idFormAdvice ?>" name="title" class="mediumtext" type="text" value="<?= htmlspecialchars($advice['title'] ?? '') ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormAdvice ?>" name="theme" class="mediumtext" type="text" value="<?= htmlspecialchars($advice['theme'] ?? '') ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormAdvice ?>" name="description" class="mediumtext" type="text" value="<?= htmlspecialchars($advice['description'] ?? '') ?>"></td>
                                        
                                    <td><?= htmlspecialchars($advice['date_publication'] ?? '') ?></td>

                                    <td><?= htmlspecialchars($advice['author'] ?? '') ?></td>

                                    <td>
                                         <div class="form-check">
                                            <input form="selectedAdvice" name="selectedAdvice" class="form-check-input" type="radio" value="<?php echo htmlspecialchars($advice['ID_ADVICE']); ?>">
                                        </div>
                                    </td>

                                    <td>
                                        <form id="<?= $idFormAdvice ?>" method="POST" action="http://localhost:8081/updateAdviceData">
                                            <button type="submit" value="<?= $advice['ID_ADVICE'] ?>">Modifier</button>
                                            <input type="hidden" name="id" value="<?= $advice['ID_ADVICE'] ?>">
                                        </form>
                                    </td>

                                    <td>
                                        <form method="POST" action="http://localhost:8081/deleteAdvice">
                                            <button type="submit" value="<?= $advice['ID_ADVICE'] ?>">Supprimer</button>
                                            <input type="hidden" name="id" value="<?= $advice['ID_ADVICE'] ?>">
                                        </form>
                                    </td>

                                </tr>

                            <?php } ?>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pagetips" value="moins">Précedent</button>
                    <button type="submit" name="pagetips" value="plus">Suivant</button>
                    <button type="submit" name="pagetips">Rafraîchir</button>

                    <input type="hidden" name="researchAdvices" value="<?php if(isset($_GET['researchAdvices'])){ echo $_GET['researchAdvices']; } ?>">
                    <input type="hidden" name="filterAdvices" value="<?php if(isset($_GET['filterAdvices'])){ echo $_GET['filterAdvices']; } ?>">
                    <input type="hidden" name="sortAdvices" value="<?php if(isset($_GET['sortAdvices'])){ echo $_GET['sortAdvices']; } ?>">
                </form>

                <h5 class="pt-5">Ajouter un conseil</h5>

                <form method="POST" action="http://localhost:8081/addAdvice">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">Titre</th>
                            <th scope="col">Thème</th>
                            <th scope="col">Description</th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="form-control mediumtext" type="text" name="title"></td>

                                <td><input class="form-control mediumtext" type="text" name="theme"></td>

                                <td><input class="form-control bigtext" type="text" name="description"></td>
                                        
                                <td><button class="button" type="submit" name="addtip">Ajouter</button></td>
                            </tr>                   
                        </tbody>
                    </table>
                </form>

                <h5 class="pt-5">Contacter par email les utilisateurs concernés par le conseil sélectionné</h5>

                <form id="selectedAdvice" method="POST" action="sendEmailSelectedAdvice.php">

                    <label>Objet</label>
                    <input type="text" name="subject" class="form-control mb-3" placeholder="Saisissez l'objet de l'email" required>

                    <label>Corps de l'email</label>
                    <textarea type="text" name="mail" class="form-control mb-3" placeholder="Rédigez l'email" required></textarea>

                    <button type="submit"class="mb-5">Envoyer</button>

                </form>
                <?php endif; ?>
            </section>

            <section id="pagenotifs" class="mt-5">
                <?php if(isset($_SESSION['listNotifs'])): ?>
                <h1>Gestion des notifications personnalisées</h1>

                <div class="col-4">
                    <?php if (isset($errorUsersMessage)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($errorUsersMessage); ?>
                        </div>
                    <?php elseif(isset($successUsersMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($successUsersMessage); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <form method="GET" action="index.php#pagenotifs">
                    <div class="row mb-3">
                        <div class="col-2">
                            <div class="input-group">
                                <input value="<?php if(isset($_GET['researchNotifs'])){ echo htmlspecialchars($_GET['researchNotifs']); }else{ echo ""; } ?>" class="form-control" name="researchNotifs" placeholder="<?php if(isset($_GET['researchNotifs']) && $_GET['researchNotifs'] != ""){ echo $_GET['researchNotifs']; }else{ ?><?php echo "Tapez votre recherche"; ?> <?php } ?>" aria-label="Search">
                                <button class="searchButton" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="col-2">
                            <select name="filterNotifs" class="selectFilter" onchange="this.form.submit()">
                                <option disabled selected><?php echo "Choisissez un type" ?></option>
                                <?php foreach($distinctTypesNotifs as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>" <?php if(isset($_GET['filterNotifs']) && $_GET['filterNotifs'] == $type){ echo 'selected'; } ?> ><?= htmlspecialchars($type) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-2">
                            <select name="sortNotifs" class="selectSort" onchange="this.form.submit()">
                                <option disabled <?php if(!isset($_GET['sortNotifs']) || $_GET['sortNotifs'] == ""){echo 'selected';} ?>>Choisissez un tri</option>
                            </select>
                        </div>
                    </div>
                </form>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Description</th>
                        <th scope="col">Type</th>
                        <th scope="col">Pour l'adhérent</th>
                        <th scope="col">Sélectionner</th>
                        <th scope="col">Modifier</th>
                        <th scope="col">Supprimer</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($_SESSION['listNotifs'] as $notif){

                                $idFormNotif = "form_notif_" . $notif['ID_NOTIFICATION'];
                        ?>
                                <tr>
                                    <th scope="row"> <?= htmlspecialchars($notif['ID_NOTIFICATION']) ?></th>

                                    <td><input class="form-control" form="<?= $idFormNotif ?>" name="title" class="mediumtext" type="text" value="<?= htmlspecialchars($notif['title'] ?? '') ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormNotif ?>" name="description" class="mediumtext" type="text" value="<?= htmlspecialchars($notif['description'] ?? '') ?>"></td>

                                    <td><input class="form-control" form="<?= $idFormNotif ?>" name="type" class="mediumtext" type="text" value="<?= htmlspecialchars($notif['type'] ?? '') ?>"></td>

                                    <td><?= htmlspecialchars($notif['username'] ?? '') ?></td>

                                    <td>
                                         <div class="form-check">
                                            <input form="selectedNotif" name="selectedNotif" class="form-check-input" type="radio" value="<?php echo htmlspecialchars($notif['ID_NOTIFICATION']); ?>">
                                        </div>
                                    </td>

                                    <td>
                                        <form id="<?= $idFormNotif ?>" method="POST" action="http://localhost:8081/updateNotificationData">
                                            <button type="submit" value="<?= $notif['ID_NOTIFICATION'] ?>">Modifier</button>
                                            <input type="hidden" name="id" value="<?= $notif['ID_NOTIFICATION'] ?>">
                                        </form>
                                    </td>

                                    <td>
                                        <form method="POST" action="http://localhost:8081/deleteNotification">
                                            <button type="submit" value="<?= $notif['ID_NOTIFICATION'] ?>">Supprimer</button>
                                            <input type="hidden" name="id" value="<?= $notif['ID_NOTIFICATION'] ?>">
                                        </form>
                                    </td>

                                </tr>

                            <?php } ?>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pagenotifs" value="moins">Précedent</button>
                    <button type="submit" name="pagenotifs" value="plus">Suivant</button>
                    <button type="submit" name="pagenotifs">Rafraîchir</button>

                    <input type="hidden" name="researchNotifs" value="<?php if(isset($_GET['researchNotifs'])){ echo $_GET['researchNotifs']; } ?>">
                    <input type="hidden" name="filterNotifs" value="<?php if(isset($_GET['filterNotifs'])){ echo $_GET['filterNotifs']; } ?>">
                    <input type="hidden" name="sortNotifs" value="<?php if(isset($_GET['sortNotifs'])){ echo $_GET['sortNotifs']; } ?>">
                </form>

                <h5 class="pt-5">Ajouter une notification</h5>

                <form method="POST" action="http://localhost:8081/addNotification">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">Titre</th>
                            <th scope="col">Description</th>
                            <th scope="col">Type</th>
                            <th scope="col">ID de l'adhérent</th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="form-control mediumtext" type="text" name="title"></td>

                                <td><input class="form-control bigtext" type="text" name="description"></td>

                                <td><input class="form-control mediumtext" type="text" name="type"></td>

                                <td><input class="form-control smalltext" type="text" name="id"></td>
                                        
                                <td><button class="button" type="submit" name="addnotif">Ajouter</button></td>
                            </tr>                   
                        </tbody>
                    </table>
                </form>

                <h5 class="pt-5">Envoyer la notification sélectionnée</h5>

                <form id="selectedNotif" method="POST" action="sendEmailSelectedNotif.php">

                    <button type="submit"class="mb-5">Envoyer</button>

                </form>
                <?php endif; ?>
            </section>

            <section id="pagemessages" class="mt-5">
                <?php if(isset($_SESSION['listMessages'])): ?>
                <h1>Contrôle des messages</h1>

                <div class="col-4">
                    <?php if (isset($errorUsersMessage)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($errorUsersMessage); ?>
                        </div>
                    <?php elseif(isset($successUsersMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($successUsersMessage); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date d'envoi</th>
                            <th scope="col">Contenu</th>
                            <th scope="col">Expéditeur</th>
                            <th scope="col">Sélectionner</th>
                            <th scope="col">Supprimer</th>
                            <th scope="col">Changer le statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($_SESSION['listMessages'] as $message){

                                $idFormMessage = "form_message_" . $message['ID_MESSAGE'];
                        ?>
                                <tr>
                                    <th scope="row"> <?= htmlspecialchars($message['ID_MESSAGE']) ?></th>

                                    <td><?= date("d/m/Y H:m:s", strtotime($message['date'])) ?></td>
                                    
                                    <td><input class="form-control" form="<?= $idFormMessage ?>" name="content" class="mediumtext" type="text" value="<?= htmlspecialchars($message['content'] ?? '') ?>"></td>

                                    <td><?= htmlspecialchars($message['sender'] ?? '') ?></td>

                                    <td>
                                        <div class="form-check">
                                            <input form="selectedMessage" name="selectedMessage" class="form-check-input" type="radio" value="<?php echo htmlspecialchars($message['ID_MESSAGE']); ?>">
                                        </div>
                                    </td>

                                    <td>
                                        <form method="POST" action="http://localhost:8081/deleteMessage">
                                            <button type="submit" value="<?= $message['ID_MESSAGE'] ?>">Supprimer</button>
                                            <input type="hidden" name="id" value="<?= $message['ID_MESSAGE'] ?>">
                                        </form>
                                    </td>

                                    <td>
                                        <form method="POST" action="http://localhost:8081/updateMessageStatus">
                                            <button type="submit" value="<?= $message['ID_MESSAGE'] ?>">Changer</button>
                                            <input type="hidden" name="id" value="<?= $message['ID_MESSAGE'] ?>">
                                        </form>
                                    </td>

                                </tr>

                            <?php } ?>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pagemessages" value="moins">Précedent</button>
                    <button type="submit" name="pagemessages" value="plus">Suivant</button>
                    <button type="submit" name="pagemessages">Rafraîchir</button>

                    <input type="hidden" name="sortMessages" value="<?php if(isset($_GET['sortMessages'])){ echo $_GET['sortMessages']; } ?>">
                </form>

                <h5 class="pt-5">Contacter par email l'expéditeur'</h5>

                <form id="selectedMessage" method="POST" action="sendEmailSelectedMessage.php">

                    <label>Objet</label>
                    <input type="text" name="subject" class="form-control mb-3" placeholder="Saisissez l'objet de l'email" required>

                    <label>Corps de l'email</label>
                    <textarea type="text" name="mail" class="form-control mb-3" placeholder="Rédigez l'email" required></textarea>

                    <button type="submit"class="mb-5">Envoyer</button>

                </form>
                <?php endif; ?>
            </section>

            <section id="pagerequests" class="mt-5">
                <?php if(isset($_SESSION['listRequests'])): ?>
                <h1>Gestion des requêtes</h1>

                <div class="col-4">
                    <?php if (isset($errorUsersMessage)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($errorUsersMessage); ?>
                        </div>
                    <?php elseif(isset($successUsersMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($successUsersMessage); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <form method="GET" action="index.php#pagerequests">
                    <div class="row mb-3">
                        <div class="col-2">
                            <div class="input-group">
                                <input value="<?php if(isset($_GET['researchRequests'])){ echo htmlspecialchars($_GET['researchRequests']); }else{ echo ""; } ?>" class="form-control" name="researchRequests" placeholder="<?php if(isset($_GET['researchRequests']) && $_GET['researchRequests'] != ""){ echo $_GET['researchRequests']; }else{ ?><?php echo "Tapez votre recherche"; ?> <?php } ?>" aria-label="Search">
                                <button class="searchButton" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="col-2">
                            <select name="filterRequests" class="selectFilter" onchange="this.form.submit()">
                                <option disabled selected><?php echo "Choisissez un sujet" ?></option>
                                <?php foreach($distinctSubjects as $subject): ?>
                                    <option value="<?= htmlspecialchars($subject) ?>" <?php if(isset($_GET['filterRequests']) && $_GET['filterRequests'] == $subject){ echo 'selected'; } ?> ><?= htmlspecialchars($subject) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-2">
                            <select name="sortRequests" class="selectSort" onchange="this.form.submit()">
                                <option disabled <?php if(!isset($_GET['sortRequests']) || $_GET['sortRequests'] == ""){echo 'selected';} ?>>Choisissez un tri</option>
                                <option value="1" <?php if(isset($_GET['sortRequests']) && $_GET['sortRequests'] == "1"){echo 'selected';} ?>>Plus anciens en premier</option>
                                <option value="2" <?php if(isset($_GET['sortRequests']) && $_GET['sortRequests'] == "2"){echo 'selected';} ?>>Plus récents en premier</option>
                            </select>
                        </div>
                    </div>
                </form>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Sujet</th>
                        <th scope="col">Requête</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Date</th>
                        <th scope="col">Sélectionner</th>
                        <th scope="col">Supprimer</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($_SESSION['listRequests'] as $request){

                                $idFormRequest = "form_request_" . $request['ID_REQUEST'];
                        ?>
                                <tr>
                                    <th scope="row"> <?= htmlspecialchars($request['ID_REQUEST']) ?></th>

                                    <td><?= htmlspecialchars($request['subject'] ?? '') ?></td>

                                    <td><?= htmlspecialchars($request['request'] ?? '') ?></td>

                                    <td><?= htmlspecialchars($request['email'] ?? '') ?></td>
                                        
                                    <td><?= htmlspecialchars($request['date'] ?? '') ?></td>

                                    <td>
                                        <div class="form-check">
                                            <input form="selectedEmailRequests" name="selectedEmailRequests[]" class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($request['email']); ?>">
                                        </div>
                                    </td>

                                    <td>
                                        <form method="POST" action="http://localhost:8081/deleteRequest">
                                            <button type="submit" value="<?= $request['ID_REQUEST'] ?>">Supprimer</button>
                                            <input type="hidden" name="id" value="<?= $request['ID_REQUEST'] ?>">
                                        </form>
                                    </td>

                                </tr>

                            <?php } ?>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pagerequests" value="moins">Précedent</button>
                    <button type="submit" name="pagerequests" value="plus">Suivant</button>
                    <button type="submit" name="pagerequests">Rafraîchir</button>

                    <input type="hidden" name="researchRequests" value="<?php if(isset($_GET['researchRequests'])){ echo $_GET['researchRequests']; } ?>">
                    <input type="hidden" name="filterRequests" value="<?php if(isset($_GET['filterRequests'])){ echo $_GET['filterRequests']; } ?>">
                    <input type="hidden" name="sortRequests" value="<?php if(isset($_GET['sortRequests'])){ echo $_GET['sortRequests']; } ?>">
                </form>

                <h5 class="pt-5">Contacter les emails sélectionnés</h5>

                <form id="selectedEmailRequests" method="POST" action="sendEmailSelectedRequests.php">

                    <label>Objet</label>
                    <input type="text" name="subject" class="form-control mb-3" placeholder="Saisissez l'objet de l'email" required>

                    <label>Corps de l'email</label>
                    <textarea type="text" name="mail" class="form-control mb-3" placeholder="Rédigez l'email" required></textarea>

                    <button type="submit"class="mb-5">Envoyer</button>

                </form>
                <?php endif; ?>
            </section>
        </div>
    </main> 
</body>
