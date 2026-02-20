<?php 

    require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    session_start();

    include("../includes/translation.php");

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
            $mail->Subject = 'Silver Happy - ' . trad('Code de confirmation');

            $text1 = trad("Bonjour");
            $text2 = trad("Votre code de confirmation Silver Happy est : ");
            $text3 = trad(". Retournez sur le site et saisissez-le afin de passer à la dernière étape de votre inscription.");

            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white; text-decoration: none;"> ' . $text1 . ' '.$nomDestinataire.',<br><br> ' . $text2 . ' <strong>'.$code.'</strong> ' . $text3 . ' </p>;
            </div>
            ';

            $mail->send();
            
            return $code;

        } catch (Exception $e) {

            return false;

        }
    }

?>