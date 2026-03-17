<?php

    require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require_once __DIR__ . '/../stripe-php/init.php';

    session_start();

    include("../includes/db.php");
    include("../includes/translation.php");

    \Stripe\Stripe::setApiKey('sk_test_51Szo1M2WiLfhQi4sPPN6NYJI4gecl8Au5rA0oxHK7grJ6H4u41IReSoXwXn0NqrtzqWW8yXOItSD7MvuuW0q0Sgt009dm7h1pa');

    $stripeSubId = null;

    if(isset($_GET['session_id'])){
        $checkoutSession = \Stripe\Checkout\Session::retrieve($_GET['session_id']);
        $stripeSubId = $checkoutSession->subscription;

        $plan = $_GET['plan'];

        $firstPriceId = null;
        $secondPriceId = null;

        if($plan == "monthly_standard"){

            $firstPriceId = "price_1Szo9b2WiLfhQi4ssJx66450";
            $secondPriceId = "price_1SzoAu2WiLfhQi4sLAKor8a2";

        }elseif($plan == "annual_standard"){

            $firstPriceId = "price_1SzoA72WiLfhQi4s6J9PFUio";
            $secondPriceId = "price_1SzoB92WiLfhQi4suEGpLvql";

        }

        $endPhaseBeforeRenewal = strtotime("+1 year");

        $schedule = \Stripe\SubscriptionSchedule::create(['from_subscription' => $stripeSubId]);

        $startPhaseBeforeRenewal = $schedule->phases[0]->start_date;

        \Stripe\SubscriptionSchedule::update($schedule->id,[
            'phases' =>[
                [
                    'items' => [['price' => $firstPriceId, 'quantity' => 1]],
                    'start_date' => $startPhaseBeforeRenewal,
                    'end_date' => $endPhaseBeforeRenewal,
                ],
                [
                    'items' => [['price' => $secondPriceId, 'quantity' => 1]],
                ],
            ],
        ]);

    }

    switch($_GET['plan']) {

        case "monthly_standard":

            $status = 1;
            $_SESSION['subscription'] = 1;
            break;
        
        case "annual_standard":

            $status = 2;
            $_SESSION['subscription'] = 2;
            break;

        case "monthly_renewal":

            $status = 5;
            $_SESSION['subscription'] = 3;
            break;

        case "annual_renewal":

            $status = 6;
            $_SESSION['subscription'] = 4;
            break;

    }

    $q = 'UPDATE USER_ SET status = :status WHERE ID_USER = :id';
    $statement = $bdd->prepare($q);
    $result = $statement->execute([
        'status' => $status,

        'id' => $_GET['id']
    ]); 

    $q = 'UPDATE CONSUMER SET ID_SUBSCRIPTION = :id_subscription, stripe_subscription_id = :stripe_subscription_id WHERE ID_USER = :id';
    $statement = $bdd->prepare($q);
    $result = $statement->execute([
        'id_subscription' =>  $_SESSION['subscription'],
        'stripe_subscription_id' =>  $stripeSubId,
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
            $mail->Subject = trad('Bienvenue chez Silver Happy !');

            $text1 = trad("Bonjour");
            $text2 = trad("Bienvenue chez Silver Happy, nous sommes fiers de vous compter parmi nos adhérents.");
            $text3 = trad("Votre inscription a bien été prise en compte et vous pouvez dès à présent profiter de nos services, nos conseils, notre boutique et bien plus encore !");
            $text4 = trad("Nous vous remercions pour votre confiance et nous réjouissons de vous accompagner dans ce qui sera les plus belles années de votre vie.");
            $text5 = trad("L’équipe Silver Happy");

            $mail->Body = '
            <div style= "background-color: #2f6f9f; padding: 35px; text-align: center;">
                <p style="color: white; text-decoration: none;"> ' . $text1 . ' ' . $name.',<br><br>' . $text2 . ' <br><br>' . $text3 . '<br><br>' . $text4 . '<br><br><br>' . $text5 . '</p>
            </div>
            ';

            $mail->send();
            
        } catch (Exception $e) {

            return false;

        }

        header("location:../connexion.php?state=".$status."&notif=registration_success");
        exit();

?>