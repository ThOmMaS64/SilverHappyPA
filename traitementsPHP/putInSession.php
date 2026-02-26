<?php

    session_start();

    include("../includes/db.php");

    if(!isset($_GET['token'])){

        header("Location:../connexion.php?error=system");
        exit();

    }

    $q = 'SELECT USER_.ID_USER, USER_.status FROM USER_ INNER JOIN TOKEN ON USER_.ID_USER = TOKEN.ID_USER AND token_date >= SUBTIME(NOW(),"00:05:00") WHERE token = :token';
    $req = $bdd->prepare($q);
    $req->execute(['token' => $_GET['token']]);
    $userInfo = $req->fetch(PDO::FETCH_ASSOC);

    if(!$userInfo){
        header("location:../connexion.php?error=system");
        exit();
    }

    $q = 'DELETE FROM TOKEN WHERE token = :token';
    $req = $bdd->prepare($q);
    $req->execute(['token' => $_GET['token']]);

    $_SESSION['id'] = $userInfo['ID_USER'];
    $_SESSION['status'] = $userInfo['status'];

    $q = 'SELECT username, email, name, surname, description, keyWord1, keyWord2, keyWord3, date_inscription, darkMode, levelFont, fontChange, cursorType, language, profilePicture FROM USER_ WHERE ID_USER = :id';
    $req = $bdd->prepare($q);
    $req->execute(['id' => $_SESSION['id']]);
    $userInfo = $req->fetch(PDO::FETCH_ASSOC);

    $_SESSION['username'] = $userInfo['username'];
    $_SESSION['email'] = $userInfo['email'];
    $_SESSION['name'] = $userInfo['name'];
    $_SESSION['surname'] = $userInfo['surname'];
    $_SESSION['darkMode'] = $userInfo['darkMode'];
    $_SESSION['levelFont'] = $userInfo['levelFont'];
    $_SESSION['fontChange'] = $userInfo['fontChange'];
    $_SESSION['cursorType'] = $userInfo['cursorType'];
    $_SESSION['language'] = $userInfo['language'];
    $_SESSION['profilePicture'] = $userInfo['profilePicture'];
    $_SESSION['date_inscription'] = $userInfo['date_inscription'];
    $_SESSION['description'] = $userInfo['description'];
    $_SESSION['keyWord1'] = $userInfo['keyWord1'];
    $_SESSION['keyWord2'] = $userInfo['keyWord2'];
    $_SESSION['keyWord3'] = $userInfo['keyWord3'];

    if($_SESSION['status'] == 1 || $_SESSION['status'] == 2 || $_SESSION['status'] == 5 || $_SESSION['status'] == 6){

        $q = 'SELECT tuto_seen FROM CONSUMER WHERE ID_USER = :id';
        $req = $bdd->prepare($q);
        $req->execute(['id' => $_SESSION['id']]);
        $userInfo = $req->fetch(PDO::FETCH_ASSOC);

        $_SESSION['tuto_seen'] = $userInfo['tuto_seen'];

    } elseif($_SESSION['status'] == 3 || $_SESSION['status'] == 4) {

        $q = 'SELECT profession FROM SERVICE_PROVIDER WHERE ID_USER = :id';
        $req = $bdd->prepare($q);
        $req->execute(['id' => $_SESSION['id']]);
        $userInfo = $req->fetch(PDO::FETCH_ASSOC);

        $_SESSION['profession'] = $userInfo['profession'];

    }

    header("location:../index.php?notif=connexion_success");

?>