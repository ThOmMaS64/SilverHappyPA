<?php
    session_start();

    include('../includes/db.php');

    switch($_POST['action']){

        case 'users':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageusers');
            exit();
            break;

        case 'shop':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageshop');
            exit();
            break;

        case 'events':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageevents');
            exit();
            break;
    
        case 'tips':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagetips');
            exit();
            break;

        case 'notifs':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagenotifs');
            exit();
            break;
    }

    header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagewelcome');
    exit();
?>