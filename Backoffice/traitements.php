<?php
    session_start();

    include('../includes/db.php');

    switch($_POST['action']){

        case 'users':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageusers');
            exit();
            break;

        
        case 'services':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageservices');
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
        
        case 'messages':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagemessages');
            exit();
            break;

        case 'requests':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagerequests');
            exit();
            break;
    }

    header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagewelcome');
    exit();
?>