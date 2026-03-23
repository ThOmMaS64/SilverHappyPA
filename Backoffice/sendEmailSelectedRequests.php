<?php

    require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    session_start();

    date_default_timezone_set('Europe/Paris');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (!isset($_POST['subject']) || empty($_POST['mail'])) {
                header('Location:index.php#pagerequests');
                exit();
        }else if (!isset($_POST['selectedEmailRequests']) || empty($_POST['selectedEmailRequests'])){
            header('Location:index.php#pagerequests');
            exit();
        }

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

            $emails = (array)$_POST["selectedEmailRequests"];

            foreach($emails as $email){

                $mail->addBCC($email);

            }
            
            $mail->isHTML(true);
            $mail->Subject = 'Silver Happy - '. $_POST["subject"]; 

            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white;">' . $_POST['mail'] . '</p>
            </div>
            ';
            
            $mail->send();

            header('location:index.php?notif=email_sent#pagerequests');
            exit();
            
        } catch (Exception $e) {

            header('location:index.php?error=email_error#pagerequests');
            exit();

        }

    }