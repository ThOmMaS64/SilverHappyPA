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

        case 'money':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagemoney');
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
        
        case 'messages':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagemessages');
            exit();
            break;

        case 'requests':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagerequests');
            exit();
            break;

        case 'captcha':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagecaptcha');
            exit();
            break;
            
    }

    header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagewelcome');
    exit();
?>