<?php

    session_start();

    include("../includes/db.php");

    if(!isset($_GET['token'])){

        header("Location: http://localhost/ProjetAnnuel/Backoffice/index.php?error=system1");
        exit();

    }

    $dataJson = file_get_contents("http://localhost:8081/getDataPutInSession?token=".urlencode($_GET['token']));

    if($dataJson){

        $response = json_decode($dataJson, true);

        if(isset($response['id']) && empty($response['error'])){

            $_SESSION['id'] = $response['id'];

            header("Location: http://localhost/ProjetAnnuel/Backoffice/#pagewelcome");

        }
    }else{
        header("Location: http://localhost/ProjetAnnuel/Backoffice/index.php?error=system2");
        exit();
    }

?>