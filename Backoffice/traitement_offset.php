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

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pageshop'])){
        
        if(!isset($_SESSION['offsetShop'])){

            $_SESSION['offsetShop'] = 0;

        }

        if($_POST['pageshop'] == "plus"){

            $_SESSION['offsetShop'] += 10;

        }elseif($_POST['pageshop'] == "moins"){

            $_SESSION['offsetShop'] -= 10;

        }

        if($_SESSION['offsetShop'] < 0){

            $_SESSION['offsetShop'] = 0;

        }

        $url = "http://localhost/ProjetAnnuel/Backoffice/index.php?";

        if(isset($_POST['researchShop']) && !empty($_POST['researchShop'])){

            $url = $url . "researchShop=" . urlencode($_POST['researchShop']);

        }
        
        if(isset($_POST['filterShop']) && !empty($_POST['filterShop'])){

            $url = $url . "&filterShop=" . urlencode($_POST['filterShop']);

        }

        if(isset($_POST['sortShop']) && !empty($_POST['sortShop'])){

            $url = $url . "&sortShop=" . urlencode($_POST['sortShop']);

        }

        $url = $url . "#pageshop";

        header('Location:' . $url);
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pageservices'])){
        
        if(!isset($_SESSION['offsetServices'])){

            $_SESSION['offsetServices'] = 0;

        }

        if($_POST['pageservices'] == "plus"){

            $_SESSION['offsetServices'] += 10;

        }elseif($_POST['pageservices'] == "moins"){

            $_SESSION['offsetServices'] -= 10;

        }

        if($_SESSION['offsetServices'] < 0){

            $_SESSION['offsetServices'] = 0;

        }

        $url = "http://localhost/ProjetAnnuel/Backoffice/index.php?";

        if(isset($_POST['researchServices']) && !empty($_POST['researchServices'])){

            $url = $url . "researchServices=" . urlencode($_POST['researchServices']);

        }
        
        if(isset($_POST['filterServices']) && !empty($_POST['filterServices'])){

            $url = $url . "&filterServices=" . urlencode($_POST['filterServices']);

        }

        if(isset($_POST['sortServices']) && !empty($_POST['sortServices'])){

            $url = $url . "&sortServices=" . urlencode($_POST['sortServices']);

        }

        $url = $url . "#pageservices";

        header('Location:' . $url);
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pagemessages'])){
        
        if(!isset($_SESSION['offsetMessages'])){

            $_SESSION['offsetMessages'] = 0;

        }

        if($_POST['pagemessages'] == "plus"){

            $_SESSION['offsetMessages'] += 10;

        }elseif($_POST['pagemessages'] == "moins"){

            $_SESSION['offsetMessages'] -= 10;

        }

        if($_SESSION['offsetMessages'] < 0){

            $_SESSION['offsetMessages'] = 0;

        }

        header('Location:http://localhost/ProjetAnnuel/Backoffice/index.php#pagemessages');
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pagerequests'])){
        
        if(!isset($_SESSION['offsetRequests'])){

            $_SESSION['offsetRequests'] = 0;

        }

        if($_POST['pagerequests'] == "plus"){

            $_SESSION['offsetRequests'] += 10;

        }elseif($_POST['pagerequests'] == "moins"){

            $_SESSION['offsetRequests'] -= 10;

        }

        if($_SESSION['offsetRequests'] < 0){

            $_SESSION['offsetRequests'] = 0;

        }

        $url = "http://localhost/ProjetAnnuel/Backoffice/index.php?";

        if(isset($_POST['researchRequests']) && !empty($_POST['researchRequests'])){

            $url = $url . "researchRequests=" . urlencode($_POST['researchRequests']);

        }
        
        if(isset($_POST['filterRequests']) && !empty($_POST['filterRequests'])){

            $url = $url . "&filterRequests=" . urlencode($_POST['filterRequests']);

        }

        if(isset($_POST['sortRequests']) && !empty($_POST['sortRequests'])){

            $url = $url . "&sortRequests=" . urlencode($_POST['sortRequests']);

        }

        $url = $url . "#pagerequests";

        header('Location:' . $url);
        exit();
    }

    ///

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pageserviceproviders'])){
        
        if(!isset($_SESSION['offsetServiceProviders'])){

            $_SESSION['offsetServiceProviders'] = 0;

        }

        if($_POST['pageserviceproviders'] == "plus"){

            $_SESSION['offsetServiceProviders'] += 10;

        }elseif($_POST['pageserviceproviders'] == "moins"){

            $_SESSION['offsetServiceProviders'] -= 10;

        }

        if($_SESSION['offsetServiceProviders'] < 0){

            $_SESSION['offsetServiceProviders'] = 0;

        }

        $url = "http://localhost/ProjetAnnuel/Backoffice/index.php?";
        
        if(isset($_POST['filterServiceProvider']) && !empty($_POST['filterServiceProvider'])){

            $url = $url . "&filterServiceProvider=" . urlencode($_POST['filterServiceProvider']);

        }

        $url = $url . "#pageservices";

        header('Location:' . $url);
        exit();
    }

?>