<?php

    require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    session_start();

    include("../includes/db.php");

    date_default_timezone_set('Europe/Paris');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (!isset($_POST['subject']) || empty($_POST['mail'])) {
                header('Location:index.php#pageshop');
                exit();
        }else if (!isset($_POST['selectedProduct']) || empty($_POST['selectedProduct'])){
            header('Location:index.php#pageshop');
            exit();
        }

        $q = 'SELECT DISTINCT USER_.email FROM USER_ INNER JOIN CONSUMER ON USER_.ID_USER = CONSUMER.ID_USER INNER JOIN SHOP_ORDER ON CONSUMER.ID_CONSUMER = SHOP_ORDER.ID_CONSUMER INNER JOIN ORDER_LINE ON SHOP_ORDER.ID_SHOP_ORDER = ORDER_LINE.ID_SHOP_ORDER WHERE ORDER_LINE.ID_PRODUCT = :id';
              
        $statement = $bdd->prepare($q);
        $statement->execute(['id' => $_POST['selectedProduct']]);
        $consumerEmails = $statement->fetchAll(PDO::FETCH_COLUMN);

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

            if(!empty($consumerEmails)){
                foreach($consumerEmails as $email){

                    $mail->addBCC($email);

                }
            }

            $mail->isHTML(true);
            $mail->Subject = 'Silver Happy - '. $_POST["subject"]; 

            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white;">' . $_POST['mail'] . '</p>
            </div>
            ';
            
            $mail->send();

            header('location:index.php?notif=email_sent#pageshop');
            exit();
            
        } catch (Exception $e) {

            header('location:index.php?error=email_error#pageshop');
            exit();

        }

    }