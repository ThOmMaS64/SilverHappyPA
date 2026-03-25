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

        if (!isset($_POST['selectedNotif']) || empty($_POST['selectedNotif'])){
            header('Location:index.php#pagenotifs');
            exit();
        }

        $id = $_POST['selectedNotif'];

        $q1 = 'SELECT title, description, ID_CONSUMER FROM NOTIFICATION WHERE ID_NOTIFICATION = :id';
        $statement = $bdd->prepare($q1);
        $statement->execute(['id' => $id]);
        $notif = $statement->fetch();

        $subject = $notif['title'];
        $object = $notif['description'];
        $id_consumer = $notif['ID_CONSUMER'];

        if (empty($subject) || empty($object)) {
                header('Location:index.php#pagenotifs');
                exit();
        }

        $q = 'SELECT USER_.email FROM USER_ INNER JOIN CONSUMER ON USER_.ID_USER = CONSUMER.ID_USER WHERE CONSUMER.ID_USER = :id';
        $statement = $bdd->prepare($q);
        $statement->execute(['id' => $id_consumer]);
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
            $mail->Subject = 'Silver Happy - '. $subject; 

            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white;">' . $object . '</p>
            </div>
            ';
            
            $mail->send();

            header('location:index.php?notif=email_sent#pagenotifs');
            exit();
            
        } catch (Exception $e) {

            header('location:index.php?error=email_error#pagenotifs');
            exit();

        }

    }