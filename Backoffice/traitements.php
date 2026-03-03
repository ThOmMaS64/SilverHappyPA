<?php
    session_start();

    include('../includes/db.php');

    switch($_POST['action']){

        case 'conn':
            $q = 'SELECT COUNT(*) FROM user_ WHERE connected = 1';
            $statement = $bdd->prepare($q);
            $statement->execute();
            $_SESSION['total'] = $statement->fetchColumn();

            $_SESSION['offsetConn'] = 0;
            $_SESSION['listconn'] = array();
            $q = 'SELECT ID_USER, username, email, connected FROM user_ LIMIT 10';
            $statement = $bdd->prepare($q);
            $statement->execute();
            $results = $statement->fetchAll();
            foreach($results as $user){
                $userConn = array([
                    'ID_USER' => $user['ID_USER'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'connected' => $user['connected']
                ]);
                $_SESSION['listconn'][] = $userConn;
            }

            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageconnexion');
            exit();
            break;

        case 'users':
            $_SESSION['offsetUsers'] = 0;
            $_SESSION['listusers'] = array();
            $q = 'SELECT ID_USER, username, birthdate, name, surname, description, email, status FROM user_ LIMIT 10';
            $statement = $bdd->prepare($q);
            $statement->execute();
            $results = $statement->fetchAll();
            foreach($results as $user){
                $registeredUser = array([
                    'ID_USER' => $user['ID_USER'],
                    'username' => $user['username'],
                    'birthdate' => $user['birthdate'],
                    'name' => $user['name'],
                    'surname' => $user['surname'],
                    'description' => $user['description'],
                    'email' => $user['email'],
                    'status' => $user['status']
                ]);
                $_SESSION['listusers'][] = $registeredUser;
            }

            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageusers');
            exit();
            break;

        case 'shop':
            $_SESSION['offsetShop'] = 0;
            $_SESSION['listshop'] = array();
            $q = 'SELECT ID_PRODUCT, name, type, description, price FROM product LIMIT 10';
            $statement = $bdd->prepare($q);
            $statement->execute();
            $results = $statement->fetchAll();
            foreach($results as $product){
                $registeredProduct = array([
                    'ID_PRODUCT' => $product['ID_PRODUCT'],
                    'name' => $product['name'],
                    'type' => $product['type'],
                    'description' => $product['description'],
                    'price' => $product['price']
                ]);
                $_SESSION['listshop'][] = $registeredProduct;
            }

            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageshop');
            exit();
            break;

        case 'events':
            $_SESSION['offsetEvents'] = 0;
            $_SESSION['listevents'] = array();
            $q = 'SELECT ID_EVENT, name, type, date_, description FROM event LIMIT 10';
            $statement = $bdd->prepare($q);
            $statement->execute();
            $results = $statement->fetchAll();
            foreach($results as $event){
                $userEvents = array([
                    'ID_EVENT' => $event['ID_EVENT'],
                    'name' => $event['name'],
                    'type' => $event['type'],
                    'date_' => $event['date_'],
                    'description' => $event['description']
                ]);
                $_SESSION['listevents'][] = $userEvents;
            }

            header('Location: http://localhost/ProjetAnnuel/Backoffice/#pageevents');
            exit();
            break;
    
        case 'tips':
            $_SESSION['offsetTips'] = 0;
            $_SESSION['listtips'] = array();
            $q = 'SELECT ID_ADVICE, title, theme, description, date_publication FROM advice LIMIT 10';
            $statement = $bdd->prepare($q);
            $statement->execute();
            $results = $statement->fetchAll();
            foreach($results as $tip){
                $tipValues = array([
                    'ID_ADVICE' => $tip['ID_ADVICE'],
                    'title' => $tip['title'],
                    'theme' => $tip['theme'],
                    'description' => $tip['description'],
                    'date_publication' => $tip['date_publication']
                ]);
                $_SESSION['listtips'][] = $tipValues;
            }

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