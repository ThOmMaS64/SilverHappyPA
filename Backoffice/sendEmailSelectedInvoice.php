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
                header('Location:index.php#pagemoney');
                exit();
        }else if (!isset($_POST['selectedInvoice']) || empty($_POST['selectedInvoice'])){
            header('Location:index.php#pagemoney');
            exit();
        }

        $q = 'SELECT email FROM USER_ INNER JOIN SERVICE_PROVIDER ON USER_.ID_USER = SERVICE_PROVIDER.ID_USER INNER JOIN INVOICE ON SERVICE_PROVIDER.ID_SERVICE_PROVIDER = INVOICE.ID_SERVICE_PROVIDER WHERE INVOICE.ID_INVOICE = :id';
        $statement = $bdd->prepare($q);
        $statement->execute(['id' => $_POST['selectedInvoice']]);
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

            header('location:index.php?notif=email_sent#pagemoney');
            exit();
            
        } catch (Exception $e) {

            header('location:index.php?error=email_error#pagemoney');
            exit();

        }

    }