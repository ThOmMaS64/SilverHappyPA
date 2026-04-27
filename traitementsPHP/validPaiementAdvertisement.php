<?php
    require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once __DIR__ . '/../stripe-php/init.php';

    session_start();

    include("../includes/db.php");
    include("../includes/translation.php");

    \Stripe\Stripe::setApiKey('sk_test_51Szo1M2WiLfhQi4sPPN6NYJI4gecl8Au5rA0oxHK7grJ6H4u41IReSoXwXn0NqrtzqWW8yXOItSD7MvuuW0q0Sgt009dm7h1pa');

    if (!isset($_GET['session_id'])) {
        header("location:../index.php?error=mail_paiement_error");
        exit();
    }

    $session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);

    if($session->payment_status === 'paid'){

        $plan = $session->metadata->plan;
        $title = $session->metadata->title;
        $description = $session->metadata->description;

        $type = ($plan == 'simple') ? 1 : (($plan == 'double') ? 2 : 3);

        $q = 'INSERT INTO ADVERTISEMENT(ID_SERVICE_PROVIDER, type, title, description, date_paiement) VALUES (?, ?, ?, ?, NOW())';
        $statement = $bdd->prepare($q);
        $statement->execute([$_SESSION['id_service_provider'], $type, $title, $description]); 

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
            $mail->addAddress($_SESSION['email']);
            
            $mail->isHTML(true);
            $mail->Subject = trad('Achat de votre pack publicitaire réussi !');

            $text1 = trad("Bonjour,");
            $text2 = trad("Votre publicité '");
            $text3 = trad("' a bien été validée. Votre profil sera mis en avant !");
            $text4 = trad("L’équipe Silver Happy");

            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white; text-decoration: none;"> ' . $text1 .',<br><br>' . $text2 . htmlspecialchars($title) . $text3 . '<br><br><br>' . $text4 . '</p>
            </div>
            ';

            $mail->send();
            
        } catch (Exception $e) {}

        header("location:../index.php?notif=advertisement_success");
        exit();

    } else {
        header("location:../index.php?error=mail_paiement_error");
        exit();
    }
?>