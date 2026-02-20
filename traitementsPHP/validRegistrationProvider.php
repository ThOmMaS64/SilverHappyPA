<?php

    require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    session_start();

    include("includes/db.php");
    include("../includes/translation.php");

    $q = 'UPDATE USER_ SET status = :status WHERE email = :email';
    $statement = $bdd->prepare($q);
    $result = $statement->execute([
        'status' => 3,
        'email' => $_GET['email']
    ]); 

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
            $mail->addAddress($_GET['email']);
            
            $mail->isHTML(true);
            $mail->Subject = trad('Bienvenue chez Silver Happy !');

            $texte1 = trad("Bonjour");
            $texte2 = trad("Bienvenue chez Silver Happy, nous sommes fiers de vous compter parmi nos prestataire.");
            $texte3 = trad("Votre inscription a bien été prise en compte et votre dossier est actuellement en cours de vérification par notre équipe. Cette étape nous permet de garantir la qualité et la fiabilité des services proposés à nos adhérents.");
            $texte4 = trad("Nous vous remercions pour votre confiance et nous réjouissons de collaborer avec vous.");
            $texte5 = trad("L’équipe Silver Happy");

            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white; text-decoration: none;"> ' . $texte1 . ' ' .$_GET['name'].',<br><br>' . $texte2 . '<br><br>' . $texte3 . '<br><br>' . $texte4 .'<br><br><br>' . $texte5 .'</p>;
            </div>
            ';

            $mail->send();
            
        } catch (Exception $e) {

            return false;

        }

        header("location:connexion.php?state=3");
        exit();

?>