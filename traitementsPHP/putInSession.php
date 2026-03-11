<?php

    session_start();

    include("../includes/db.php");

    if(!isset($_GET['token'])){

        header("Location:../connexion.php?error=system1");
        exit();

    }

    $dataJson = file_get_contents("http://localhost:8081/getDataPutInSession?token=".urlencode($_GET['token']));

    if($dataJson){

        $response = json_decode($dataJson, true);

        if(isset($response['id']) && empty($response['error'])){

            $_SESSION['id'] = $response['id'];
            $_SESSION['status'] = $response['status'];
            $_SESSION['username'] = $response['username'];
            $_SESSION['email'] = $response['email'];
            $_SESSION['name'] = $response['name'];
            $_SESSION['surname'] = $response['surname'];
            $_SESSION['darkMode'] = $response['darkMode'];
            $_SESSION['levelFont'] = $response['levelFont'];
            $_SESSION['fontChange'] = $response['fontChange'];
            $_SESSION['cursorType'] = $response['cursorType'];
            $_SESSION['language'] = $response['language'];
            $_SESSION['profilePicture'] = $response['profilePicture'];
            $_SESSION['date_inscription'] = $response['dateInscription'];
            $_SESSION['description'] = $response['description'];
            $_SESSION['keyWord1'] = $response['keyWord1'];
            $_SESSION['keyWord2'] = $response['keyWord2'];
            $_SESSION['keyWord3'] = $response['keyWord3'];

            if(isset($response['tuto_seen'])){

                $_SESSION['tuto_seen'] = $response['tutoSeen'];

            }else if(isset($response['profession'])){

                $_SESSION['profession'] = $response['profession'];

            }

            header("location:../index.php?notif=connexion_success");

        }
    }else{
        header("Location:../connexion.php?error=system2");
        exit();
    }

?>