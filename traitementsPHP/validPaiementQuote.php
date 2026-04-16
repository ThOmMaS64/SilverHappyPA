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

        $idConsumer = $infos['ID_CONSUMER'];

        $reqQuotes = $bdd->prepare('SELECT QUOTE.ID_SERVICE, SERVICE_PROVIDER.ID_USER as provider_id FROM QUOTE INNER JOIN SERVICE_PROVIDER ON QUOTE.ID_SERVICE_PROVIDER = SERVICE_PROVIDER.ID_SERVICE_PROVIDER WHERE QUOTE.status = 1 AND QUOTE.ID_CONSUMER = :id_consumer');
        $reqQuotes->execute(['id_consumer' => $idConsumer]);
        $quotesToPay = $reqQuotes->fetchAll(PDO::FETCH_ASSOC);

        $bdd->prepare('UPDATE QUOTE SET status = 2 WHERE status = 1 AND ID_CONSUMER = :id')->execute(['id' => $idConsumer]);

        foreach ($quotesToPay as $quote) {
            $reqDisc = $bdd->prepare('SELECT ID_DISCUSSION FROM DISCUSSION WHERE ID_SERVICE = :id_service AND ((user1_id = :u1 AND user2_id = :u2) OR (user1_id = :u2 AND user2_id = :u1)) LIMIT 1');
            $reqDisc->execute(['id_service' => $quote['ID_SERVICE'], 'u1' => $_SESSION['id'], 'u2' => $quote['provider_id']]);
            
            if ($idDisc = $reqDisc->fetchColumn()) {
                $bdd->prepare('INSERT INTO MESSAGE (content, date, sender_id, ID_DISCUSSION) VALUES (:msg, NOW(), :sender, :id_disc)')->execute([
                    'msg' => trad("Bonjour, je viens de régler le devis pour la prestation."),
                    'sender' => $_SESSION['id'],
                    'id_disc' => $idDisc
                ]);
            }
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
            $mail->Subject = trad('Paiement du devis réussi !');

            $text1 = trad("Bonjour,");
            $text2 = trad("Le paiement du devis de la prestation de service est réglé avec succés.");
            $text3 = trad("L’équipe Silver Happy");

            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white; text-decoration: none;"> ' . $text1 .',<br><br>' . $text2 . '<br><br><br>' . $text3 . '</p>
            </div>
            ';

            $mail->send();
            
        } catch (Exception $e) {

            return false;

        }

        header("location:../index.php?notif=quote_paiement_success");
        exit();

    }

?>