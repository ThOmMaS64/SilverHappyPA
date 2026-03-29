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

        $q = 'INSERT INTO PARTICIPATE(ID_EVENT, ID_CONSUMER) VALUES (?, ?)';
        $statement = $bdd->prepare($q);
        $result = $statement->execute([$_GET['id_event'], $infos['ID_CONSUMER']]); 

        $q = 'UPDATE EVENT SET nb_inscription = nb_inscription + 1 WHERE ID_EVENT = :id_event';
        $statement = $bdd->prepare($q);
        $result = $statement->execute([
            'id_event' => $_GET['id_event']
        ]); 

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
            $mail->Subject = trad('Inscription à l\'événement réussie !');

            $text1 = trad("Bonjour,");
            $text2 = trad("Votre inscription à l'événement ");
            $text3 = trad(" est validée. À très vite !");
            $text4 = trad("L’équipe Silver Happy");

            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white; text-decoration: none;"> ' . $text1 .',<br><br>' . $text2 . $_GET['name'] . $text3 . '<br><br><br>' . $text4 . '</p>
            </div>
            ';

            $mail->send();
            
        } catch (Exception $e) {

            return false;

        }

        header("location:http://localhost:8081/generateEventInvoice?id_event=" . $_GET['id_event'] . "&id_consumer=" . $infos['ID_CONSUMER']);
        exit();

    }

?>