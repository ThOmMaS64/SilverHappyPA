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
                header('Location:index.php#pagetips');
                exit();
        }else if (!isset($_POST['selectedAdvice']) || empty($_POST['selectedAdvice'])){
            header('Location:index.php#pagetips');
            exit();
        }

        $q = 'SELECT email FROM USER_ INNER JOIN USER_INTERACTION_ADVICE ON USER_.ID_USER = USER_INTERACTION_ADVICE.ID_USER WHERE USER_INTERACTION_ADVICE.ID_ADVICE = :id';
        $statement = $bdd->prepare($q);
        $statement->execute(['id' => $_POST['selectedAdvice']]);
        $consumerEmails = $statement->fetchAll(PDO::FETCH_COLUMN);

        $q2 = 'SELECT email FROM USER_ INNER JOIN SERVICE_PROVIDER ON USER_.ID_USER = SERVICE_PROVIDER.ID_USER INNER JOIN ADVICE ON SERVICE_PROVIDER.ID_SERVICE_PROVIDER = ADVICE.ID_SERVICE_PROVIDER WHERE ADVICE.ID_ADVICE = :id AND SERVICE_PROVIDER.ID_SERVICE_PROVIDER != 1';
        $statement2 = $bdd->prepare($q2);
        $statement2->execute(['id' => $_POST['selectedAdvice']]);
        $serviceProviderEmail = $statement2->fetchColumn();

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
            if(!empty($serviceProviderEmail)){
                $mail->addBCC($serviceProviderEmail);
            }
            
            $mail->isHTML(true);
            $mail->Subject = 'Silver Happy - '. $_POST["subject"]; 

            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white;">' . $_POST['mail'] . '</p>
            </div>
            ';
            
            $mail->send();

            header('location:index.php?notif=email_sent#pagetips');
            exit();
            
        } catch (Exception $e) {

            header('location:index.php?error=email_error#pagetips');
            exit();

        }

    }