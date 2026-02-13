<?php

    require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

session_start();

include('../includes/db.php');

date_default_timezone_set('Europe/Paris');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['username']) || empty($_POST['username'])) {
            header('Location: passwordForgotten.php?error=missing_field');
            exit();
    }else {
        $username = $_POST['username'];
}

$q = 'SELECT ID_USER, email FROM USER_  WHERE username = :username OR email = :username';
$statement = $bdd->prepare($q);
$statement->execute(['username' => $username]);
$user = $statement->fetch();

if (!$user) {
    header('Location: passwordForgotten.php?error=no_account_found');
    exit();
}

$mailReceveur = trim($user['email']);

$token = bin2hex(random_bytes(32));
$tokenDate = date('Y-m-d H:i:s',time());

$query = 'INSERT INTO TOKEN (token, token_date, ID_USER) VALUES (:token, :token_date, :id)';
$statement = $bdd -> prepare($query);
$statement -> execute([
    'token' => $token,
    'token_date' => $tokenDate,
    'id' => $user['ID_USER']
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

        $mail->setFrom('silverhappyoff@gmail.com', 'Silver Happy');
        $mail->addAddress($mailReceveur);
        
        $mail->isHTML(true);
        $mail->Subject = 'Silver Happy - Mot de passe oublié'; 
        $mail->Body = '
        <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
            <a  href="http://localhost/ProjetAnnuel/resetPassword.php?token=' . $token . '" style="color: white; text-decoration: none;">Cliquez <strong>ICI</strong> afin de réinitialiser votre mot de passe Silver Happy. Vous pourrez en configurer un nouveau et retrouver l\'accès à votre compte !.<br><br><br>L\'équipe Silver Happy<br><br><small>Lien valide pour une durée de 1 heure à compté de l\'envoi.</small></a>
        </div>
        ';

        $_SESSION['notif'] = "Un mail vous permettant de modifier votre mot de passe vous a été envoyé à l'adresse email suivante : " . $mailReceveur; 
        
        $mail->send();

        header('location:../connexion.php?notif=forgottentPasswordEmailSent&email='.$mailReceveur);
        
    } catch (Exception $e) {

        return false;

    }

}

?>