<?php
    session_start();

    include('../includes/db.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pageConn'])){
        if($_POST['pageConn'] == 'plus'){
            $_SESSION['offsetConn'] += 10;
        }
        if($_POST['pageConn'] == 'moins'){
            $_SESSION['offsetConn'] -= 10;
        }
        if($_SESSION['offsetConn']<0){
            $_SESSION['offsetConn'] = 0;
        }
        
        $_SESSION['listconn'] = array();
        $q = 'SELECT ID_USER, username, email, connected FROM user_ LIMIT 10 OFFSET :offset';
        $statement = $bdd->prepare($q);
        $statement->bindValue(':offset', $_SESSION['offsetConn'], PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        foreach($results as $user){
            $userConn = [
                'ID_USER' => $user['ID_USER'],
                'username' => $user['username'],
                'email' => $user['email'],
                'connected' => $user['connected']
            ];
            $_SESSION['listconn'][] = $userConn;
        }

        header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageconnexion');
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pageUsers'])){
        
        if(!isset($_SESSION['offsetUsers'])){

            $_SESSION['offsetUsers'] = 0;

        }

        if($_POST['pageUsers'] == "plus"){

            $_SESSION['offsetUsers'] += 10;

        }elseif($_POST['pageUsers'] == "moins"){

            $_SESSION['offsetUsers'] -= 10;

        }

        if($_SESSION['offsetUsers'] < 0){

            $_SESSION['offsetUsers'] = 0;

        }

        $url = "http://localhost/ProjetAnnuel/Backoffice/index.php?";

        if(isset($_POST['research']) && !empty($_POST['research'])){

            $url = $url . "research=" . urlencode($_POST['research']);

        }
        
        if(isset($_POST['filter']) && !empty($_POST['filter'])){

            $url = $url . "&filter=" . urlencode($_POST['filter']);

        }

        if(isset($_POST['sort']) && !empty($_POST['sort'])){

            $url = $url . "&sort=" . urlencode($_POST['sort']);

        }

        $url = $url . "#pageusers";

        header('Location:' . $url);
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pageShop'])){
        if($_POST['pageShop'] == 'plus'){
            $_SESSION['offsetShop'] += 10;
        }
        if($_POST['pageShop'] == 'moins'){
            $_SESSION['offsetShop'] -= 10;
        }
        if($_SESSION['offsetShop']<0){
            $_SESSION['offsetShop'] = 0;
        }

        $_SESSION['listshop'] = array();
        $q = 'SELECT ID_PRODUCT, name, type, description, price FROM product LIMIT 10 OFFSET :offset';
        $statement = $bdd->prepare($q);
        $statement->bindValue(':offset', $_SESSION['offsetShop'], PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        foreach($results as $product){
            $registeredProduct = [
                'ID_PRODUCT' => $product['ID_PRODUCT'],
                'name' => $product['name'],
                'type' => $product['type'],
                'description' => $product['description'],
                'price' => $product['price']
            ];
            $_SESSION['listshop'][] = $registeredProduct;
        }

        header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageshop');
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pageEvents'])){
        if($_POST['pageEvents'] == 'plus'){
            $_SESSION['offsetEvents'] += 10;
        }
        if($_POST['pageEvents'] == 'moins'){
            $_SESSION['offsetEvents'] -= 10;
        }
        if($_SESSION['offsetEvents']<0){
            $_SESSION['offsetEvents'] = 0;
        }

        $_SESSION['listevents'] = array();
        $q = 'SELECT ID_EVENT, name, type, date_, description FROM event LIMIT 10 OFFSET :offset';
        $statement = $bdd->prepare($q);
        $statement->bindValue(':offset', $_SESSION['offsetEvents'], PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        foreach($results as $event){
            $userEvents = [
                'ID_EVENT' => $event['ID_EVENT'],
                'name' => $event['name'],
                'type' => $event['type'],
                'date_' => $event['date_'],
                'description' => $event['description']
            ];
            $_SESSION['listevents'][] = $userEvents;
        }

        header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageevents');
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pageTips'])){
        if($_POST['pageTips'] == 'plus'){
            $_SESSION['offsetTips'] += 10;
        }
        if($_POST['pageTips'] == 'moins'){
            $_SESSION['offsetTips'] -= 10;
        }
        if($_SESSION['offsetTips']<0){
            $_SESSION['offsetTips'] = 0;
        }

        $_SESSION['listtips'] = array();
        $q = 'SELECT ID_ADVICE, title, theme, description, date_publication FROM advice LIMIT 10 OFFSET :offset';
        $statement = $bdd->prepare($q);
        $statement->bindValue(':offset', $_SESSION['offsetTips'], PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        foreach($results as $tip){
            $tipValues = [
                'ID_ADVICE' => $tip['ID_ADVICE'],
                'title' => $tip['title'],
                'theme' => $tip['theme'],
                'description' => $tip['description'],
                'date_publication' => $tip['date_publication']
            ];
            $_SESSION['listtips'][] = $tipValues;
        }

        header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagetips');
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pageNotifs'])){
        if($_POST['pageNotifs'] == 'plus'){
            $_SESSION['offsetNotifs'] += 10;
        }
        if($_POST['pageNotifs'] == 'moins'){
            $_SESSION['offsetNotifs'] -= 10;
        }
        if($_SESSION['offsetNotifs']<0){
            $_SESSION['offsetNotifs'] = 0;
        }

        $_SESSION['listnotifs'] = array();
        $q = 'SELECT ID_NOTIFICATION, title, description, type FROM notification LIMIT 10 OFFSET :offset';
        $statement = $bdd->prepare($q);
        $statement->bindValue(':offset', $_SESSION['offsetNotifs'], PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        foreach($results as $notif){
            $notifValues = [
                'ID_NOTIFICATION' => $notif['ID_NOTIFICATION'],
                'title' => $notif['title'],
                'description' => $notif['description'],
                'type' => $notif['type']
            ];
            $_SESSION['listnotifs'][] = $notifValues;
        }

        header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagenotifs');
        exit();
    }
?>