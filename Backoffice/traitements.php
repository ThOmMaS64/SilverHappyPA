<?php
    session_start();

    include('../includes/db.php');

    switch($_POST['action']){

        case 'conn':
            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageconnexion');
            exit();
            break;

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
            $_SESSION['offsetNotifs'] = 0;
            $_SESSION['listnotifs'] = array();
            $q = 'SELECT ID_NOTIFICATION, title, description, type FROM notification LIMIT 10';
            $statement = $bdd->prepare($q);
            $statement->execute();
            $results = $statement->fetchAll();
            foreach($results as $notif){
                $notifValues = array([
                    'ID_NOTIFICATION' => $notif['ID_NOTIFICATION'],
                    'title' => $notif['title'],
                    'description' => $notif['description'],
                    'type' => $notif['type']
                ]);
                $_SESSION['listnotifs'][] = $notifValues;
            }

            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagenotifs');
            exit();
            break;
    }

    header('Location: http://localhost/ProjetAnnuel/Backoffice/#pagewelcome');
    exit();
?>