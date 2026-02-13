<?php

    session_start();

    require_once __DIR__ . '/../stripe-php/init.php';

    \Stripe\Stripe::setApiKey('sk_test_51Szo1M2WiLfhQi4sPPN6NYJI4gecl8Au5rA0oxHK7grJ6H4u41IReSoXwXn0NqrtzqWW8yXOItSD7MvuuW0q0Sgt009dm7h1pa');

    $plan = $_GET['plan'] ?? null;
    $planId = "";

    switch($plan) {

        case "monthly_standard":

            $planId = "price_1Szo9b2WiLfhQi4ssJx66450";
            break;
        
        case "annual_standard":

            $planId = "price_1SzoA72WiLfhQi4s6J9PFUio";
            break;
        
        case "monthly_renewal":

            $planId = "price_1SzoAu2WiLfhQi4sLAKor8a2";
            break;

        case "annual_renewal":

            $planId = "price_1SzoB92WiLfhQi4suEGpLvql";
            break;

    }

    $domain = 'http://localhost/ProjetAnnuel';

    try{

        $checkout_session = \Stripe\Checkout\Session::create([
            "mode" => "subscription",
            "success_url" => $domain . "/traitementsPHP/validRegistrationCustomer.php?plan=" . $plan . "&id=" .$_SESSION['id'],
            "cancel_url" => $domain . "/subscribe.php?error=failed",
            "line_items" => [
                [
                    "quantity" => 1,
                    "price" => $planId,
                ]
            ]
        ]);

        http_response_code(303);
        header("location:" . $checkout_session->url);
        exit();

    } catch(\Stripe\Exception\ApiErrorException $e){

        header("location:../subscribe.php?error=failed");
        exit();
    }

?>