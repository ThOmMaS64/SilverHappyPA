<?php

    require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    include("../includes/db.php");

    switch($_GET['plan']) {

        case "monthly_standard":

            $status = 1;
            $_SESSION['subscription'] = "nm";
            break;
        
        case "annual_standard":

            $status = 2;
            $_SESSION['subscription'] = "na";
            break;

        case "monthly_renewal":

            $status = 5;
            $_SESSION['subscription'] = "rm";
            break;

        case "annual_renewal":

            $status = 6;
            $_SESSION['subscription'] = "ra";
            break;

    }

    $q = 'UPDATE USER_ SET status = :status WHERE ID_USER = :id';
    $statement = $bdd->prepare($q);
    $result = $statement->execute([
        'status' => $status,
        'id' => $_GET['id']
    ]); 

    $q = 'UPDATE USER_ SET SUBSCRIPTION = :subscription WHERE ID_USER = :id';
    $statement = $bdd->prepare($q);
    $result = $statement->execute([
        'subscription' => $_SESSION['subscription'],
        'id' => $_GET['id']
    ]); 

    $qUser = 'SELECT email, name FROM USER_ WHERE ID_USER = :id';
    $reqUser = $bdd->prepare($qUser);
    $reqUser->execute(['id' => $_GET['id']]);
    $userInfo = $reqUser->fetch(PDO::FETCH_ASSOC);

    $email = $userInfo['email'];
    $name = $userInfo['name'];

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
            $mail->Subject = 'Bienvenue chez Silver Happy !';
            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white; text-decoration: none;">Bonjour '.$name.',<br><br>Bienvenue chez Silver Happy, nous sommes fiers de vous compter parmi nos adhérants.<br><br>Votre inscription a bien été prise en compte et vous pouvez dès à présent profiter de nos services, nos conseils, notre boutique et bien plus encore !<br><br>Nous vous remercions pour votre confiance et nous réjouissons de vous accompagner dans ce qui sera les plus belles années de votre vie.<br><br><br>L’équipe Silver Happy</p>;
            </div>
            ';

            $mail->send();
            
        } catch (Exception $e) {

            return false;

        }

        header("location:../connexion.php?state=".$status."&notif=registration_success");
        exit();

?>