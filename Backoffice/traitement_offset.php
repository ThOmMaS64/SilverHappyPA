<?php
    session_start();

    include('../includes/db.php');

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

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pagetips'])){
        
        if(!isset($_SESSION['offsetAdvices'])){

            $_SESSION['offsetAdvices'] = 0;

        }

        if($_POST['pagetips'] == "plus"){

            $_SESSION['offsetAdvices'] += 10;

        }elseif($_POST['pagetips'] == "moins"){

            $_SESSION['offsetAdvices'] -= 10;

        }

        if($_SESSION['offsetAdvices'] < 0){

            $_SESSION['offsetAdvices'] = 0;

        }

        $url = "http://localhost/ProjetAnnuel/Backoffice/index.php?";

        if(isset($_POST['researchAdvices']) && !empty($_POST['researchAdvices'])){

            $url = $url . "researchAdvices=" . urlencode($_POST['researchAdvices']);

        }
        
        if(isset($_POST['filterAdvices']) && !empty($_POST['filterAdvices'])){

            $url = $url . "&filterAdvices=" . urlencode($_POST['filterAdvices']);

        }

        if(isset($_POST['sortAdvices']) && !empty($_POST['sortAdvices'])){

            $url = $url . "&sortAdvices=" . urlencode($_POST['sortAdvices']);

        }

        $url = $url . "#pagetips";

        header('Location:' . $url);
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pageevents'])){
        
        if(!isset($_SESSION['offsetEvents'])){

            $_SESSION['offsetEvents'] = 0;

        }

        if($_POST['pageevents'] == "plus"){

            $_SESSION['offsetEvents'] += 10;

        }elseif($_POST['pageevents'] == "moins"){

            $_SESSION['offsetEvents'] -= 10;

        }

        if($_SESSION['offsetEvents'] < 0){

            $_SESSION['offsetEvents'] = 0;

        }

        $url = "http://localhost/ProjetAnnuel/Backoffice/index.php?";

        if(isset($_POST['researchEvents']) && !empty($_POST['researchEvents'])){

            $url = $url . "researchEvents=" . urlencode($_POST['researchEvents']);

        }
        
        if(isset($_POST['filterEvents']) && !empty($_POST['filterEvents'])){

            $url = $url . "&filterEvents=" . urlencode($_POST['filterEvents']);

        }

        if(isset($_POST['sortEvents']) && !empty($_POST['sortEvents'])){

            $url = $url . "&sortEvents=" . urlencode($_POST['sortEvents']);

        }

        $url = $url . "#pageevents";

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