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

        $qItems = 'SELECT ORDER_LINE.quantity, ORDER_LINE.ID_PRODUCT FROM ORDER_LINE INNER JOIN SHOP_ORDER ON ORDER_LINE.ID_SHOP_ORDER = SHOP_ORDER.ID_SHOP_ORDER WHERE SHOP_ORDER.ID_CONSUMER = :id_consumer AND SHOP_ORDER.status = 0';
        $reqItems = $bdd->prepare($qItems);
        $reqItems->execute(['id_consumer' => $infos['ID_CONSUMER']]);
        $cartItems = $reqItems->fetchAll(PDO::FETCH_ASSOC);

        $q = 'UPDATE SHOP_ORDER SET status = 1 WHERE ID_CONSUMER = :id_consumer AND status = 0';
        $statement = $bdd->prepare($q);
        $result = $statement->execute(['id_consumer' => $infos['ID_CONSUMER']]); 

        $q = 'UPDATE PRODUCT SET stock = stock - :quantity WHERE ID_PRODUCT = :id_product';
        $req = $bdd->prepare($q);
        foreach($cartItems as $item){
            $req->execute([
                'quantity' => $item['quantity'],
                'id_product' => $item['ID_PRODUCT']
            ]);
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
            $mail->Subject = trad('Achat réussi !');

            $text1 = trad("Bonjour,");
            $text2 = trad("Votra achat a été réalisé avec succès. Retrouvez dès à présent votre facture jointe ainsi que sur votre profil Silver Happy");
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

        header("location:../cart.php?notif=paiement_success");
        exit();

    }

?>