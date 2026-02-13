<?php 

    require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    function sendCodeFunc($emailDestinataire, $nomDestinataire){

        $code = rand(100000, 999999);

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
            $mail->addAddress($emailDestinataire);
            
            $mail->isHTML(true);
            $mail->Subject = 'Silver Happy - Code de confirmation';
            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white; text-decoration: none;">Bonjour '.$nomDestinataire.',<br><br>Votre code de confirmation Silver Happy est : <strong>'.$code.'</strong>. Retournez sur le site et saisissez-le afin de passer à la dernière étape de votre inscription.</p>;
            </div>
            ';

            $mail->send();
            
            return $code;

        } catch (Exception $e) {

            return false;

        }
    }

?>