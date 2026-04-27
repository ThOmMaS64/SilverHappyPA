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
            $_SESSION['banned'] = $response['banned'];
            $_SESSION['showZoom'] = $response['showZoom'];
            $_SESSION['showAudio'] = $response['showAudio'];

            if(isset($response['tutoSeen']) && $response['tutoSeen'] != ""){

                $_SESSION['tuto_seen'] = $response['tutoSeen'];
                $_SESSION['birth_date'] = $response['birth_date'];
                $_SESSION['id_consumer'] = $response['idConsumer'];

            }else if(isset($response['profession']) && $response['profession'] != ""){

                $_SESSION['profession'] = $response['profession'];
                $_SESSION['id_service_provider'] = $response['idServiceProvider'];
                
            }

            if($_SESSION['banned'] == 1){
                header("location:../indexBanned.php");
                exit();
            }

            header("location:../index.php?notif=connexion_success");

        }
    }else{
        header("Location:../connexion.php?error=system2");
        exit();
    }

?>