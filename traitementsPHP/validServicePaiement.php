<?php

    require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require_once __DIR__ . '/../stripe-php/init.php';

    session_start();

    include("../includes/db.php");
    include("../includes/translation.php");

    \Stripe\Stripe::setApiKey('sk_test_51Szo1M2WiLfhQi4sPPN6NYJI4gecl8Au5rA0oxHK7grJ6H4u41IReSoXwXn0NqrtzqWW8yXOItSD7MvuuW0q0Sgt009dm7h1pa');

    $session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);

    if($session->payment_status === 'paid'){

        $q = 'SELECT USER_.email, CONSUMER.ID_CONSUMER FROM USER_ INNER JOIN CONSUMER ON USER_.ID_USER = CONSUMER.ID_USER WHERE USER_.ID_USER = :id';
        $req = $bdd->prepare($q);
        $req->execute(['id' => $_SESSION['id']]);
        $infos = $req->fetch(PDO::FETCH_ASSOC);

        if(isset($_GET['id_service_slot']) && !empty($_GET['id_service_slot'])){

            $q = 'INSERT INTO SERVICE_BOOKING(ID_SERVICE_SLOT, ID_CONSUMER, booked_at) VALUES (?, ?, ?)';
            $statement = $bdd->prepare($q);
            $result = $statement->execute([$_GET['id_service_slot'], $infos['ID_CONSUMER'], date('Y-m-d H:i:s')]); 

            $q = 'UPDATE SERVICE_SLOT SET is_booked = 1 WHERE ID_SERVICE_SLOT = :id_service_slot';
            $statement = $bdd->prepare($q);
            $result = $statement->execute(['id_service_slot' => $_GET['id_service_slot']]); 

            $q = 'SELECT SERVICE_SLOT.start_time, SERVICE_SLOT.end_time, SERVICE_SLOT.ID_SERVICE, SERVICE.is_at_consumer_home, SERVICE.ID_WORK_ADDRESS, SERVICE.cost FROM SERVICE_SLOT JOIN SERVICE ON SERVICE_SLOT.ID_SERVICE = SERVICE.ID_SERVICE WHERE SERVICE_SLOT.ID_SERVICE_SLOT = :id_slot';
            $req = $bdd->prepare($q);
            $req->execute(['id_slot' => $_GET['id_service_slot']]);
            $slotInfos = $req->fetch(PDO::FETCH_ASSOC);

            $q = 'INSERT INTO INTERVENTION(cost, status, start_date, end_date, is_at_consumer_home, ID_WORK_ADDRESS, ID_SERVICE) VALUES (:cost, 1, :start, :end, :at_home, :id_address, :id_service)';
            $req = $bdd->prepare($q);
            $req->execute([
                'cost'       => $slotInfos['cost'],
                'start'      => $slotInfos['start_time'],
                'end'        => $slotInfos['end_time'],
                'at_home'    => $slotInfos['is_at_consumer_home'],
                'id_address' => $slotInfos['ID_WORK_ADDRESS'],
                'id_service' => $slotInfos['ID_SERVICE']
            ]);
            $idIntervention = $bdd->lastInsertId();

            $q = 'SELECT SERVICE_PROVIDER.ID_SERVICE_PROVIDER FROM OFFER JOIN SERVICE_PROVIDER ON OFFER.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER WHERE OFFER.ID_SERVICE = :id_service';
            $req = $bdd->prepare($q);
            $req->execute(['id_service' => $slotInfos['ID_SERVICE']]);
            $provider = $req->fetch(PDO::FETCH_ASSOC);

            $bdd->prepare('INSERT INTO DO(ID_SERVICE_PROVIDER, ID_INTERVENTION) VALUES (?, ?)')
                ->execute([$provider['ID_SERVICE_PROVIDER'], $idIntervention]);

            $bdd->prepare('INSERT INTO CALL_(ID_CONSUMER, ID_INTERVENTION) VALUES (?, ?)')
                ->execute([$infos['ID_CONSUMER'], $idIntervention]);

        }else{

            $q = 'INSERT INTO SERVICE_BOOKING(ID_CONSUMER, booked_at) VALUES (?, ?)';
            $statement = $bdd->prepare($q);
            $result = $statement->execute([$infos['ID_CONSUMER'], date('Y-m-d H:i:s')]);

            $qService = 'SELECT cost, is_at_consumer_home, ID_WORK_ADDRESS FROM SERVICE WHERE ID_SERVICE = :id_service';
            $reqS = $bdd->prepare($qService);
            $reqS->execute(['id_service' => $_GET['id_service']]);
            $serviceData = $reqS->fetch(PDO::FETCH_ASSOC);

            $qIntervention = 'INSERT INTO INTERVENTION(cost, status, is_at_consumer_home, ID_WORK_ADDRESS, ID_SERVICE) VALUES (:cost, 1, :at_home, :id_address, :id_service)';
            $reqI = $bdd->prepare($qIntervention);
            $reqI->execute([
                'cost'       => $serviceData['cost'],
                'at_home'    => $serviceData['is_at_consumer_home'],
                'id_address' => $serviceData['ID_WORK_ADDRESS'],
                'id_service' => $_GET['id_service']
            ]);
            $idIntervention = $bdd->lastInsertId();

            $qProvider = 'SELECT SERVICE_PROVIDER.ID_SERVICE_PROVIDER FROM OFFER JOIN SERVICE_PROVIDER ON OFFER.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER WHERE OFFER.ID_SERVICE = :id_service';
            $reqP = $bdd->prepare($qProvider);
            $reqP->execute(['id_service' => $_GET['id_service']]);
            $provider = $reqP->fetch(PDO::FETCH_ASSOC);

            $bdd->prepare('INSERT INTO DO(ID_SERVICE_PROVIDER, ID_INTERVENTION) VALUES (?, ?)')
                ->execute([$provider['ID_SERVICE_PROVIDER'], $idIntervention]);

            $bdd->prepare('INSERT INTO CALL_(ID_CONSUMER, ID_INTERVENTION) VALUES (?, ?)')
                ->execute([$infos['ID_CONSUMER'], $idIntervention]);

        }

        $email = $infos['email'];

    $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'silverhappyoff@gmail.com';
            $mail->Password   = 'zfnv psee fxjy eyqu';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('silverhappyoff@gmail.com', 'Silver Happy');
            $mail->addAddress($email);
            
            $mail->isHTML(true);
            $mail->Subject = trad('Inscription à une prestation réussie !');

            $text1 = trad("Bonjour,");
            $text2 = trad("Votre inscription à la prestation ");
            $text3 = trad(" est validée.");
            $text4 = trad("L’équipe Silver Happy");

            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white; text-decoration: none;"> ' . $text1 .',<br><br>' . $text2 . htmlspecialchars($_GET['type']) . $text3 . '<br><br><br>' . $text4 . '</p>
            </div>
            ';

            $mail->send();
            
        } catch (Exception $e) {

            return false;

        }

        header("location:http://localhost:8081/generateServiceInvoice?id_service=" . $_GET['id_service'] . "&id_consumer=" . $infos['ID_CONSUMER']);
        exit();

    }

?>