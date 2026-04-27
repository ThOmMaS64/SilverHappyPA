<?php

    session_start();

    include("../includes/db.php");

    require_once __DIR__ . '/../stripe-php/init.php';

    \Stripe\Stripe::setApiKey('sk_test_51Szo1M2WiLfhQi4sPPN6NYJI4gecl8Au5rA0oxHK7grJ6H4u41IReSoXwXn0NqrtzqWW8yXOItSD7MvuuW0q0Sgt009dm7h1pa');

    $domain = 'http://localhost/ProjetAnnuel';

    if(isset($_POST['plan']) && isset($_POST['title']) && isset($_POST['description'])){

        if($_POST['plan'] == 'simple'){

            $name = "Publicité pack simple";
            $price = 49;

        }elseif($_POST['plan'] == 'double'){

            $name = "Publicité pack double";
            $price = 99;
        
        }elseif($_POST['plan'] == 'ultra'){

            $name = "Publicité pack ultra";
            $price = 119;
        
        }else{

            header("location:advertisement.php?paiement_error");
            return;

        }

    }else{

        header("location:advertisement.php?paiement_error");
        return;

    }

try{

    $checkout_session = \stripe\checkout\Session::create([
        "mode" => "payment",
        "success_url" => $domain . "/traitementsPHP/validPaiementAdvertisement.php?session_id={CHECKOUT_SESSION_ID}&plan=" . $_POST['PLAN'] ,
        "cancel_url" => $domain . "/advertisement.php?error=paiement_error",
        "line_items" => [
            [
                "quantity" => 1,
                "price_data" => [
                    "currency" => "eur",
                    "unit_amount" => $price*100,
                    "product_data" => [
                        "name" => $name
                    ]
                ]
            ]
        ],

        "metadata" => [
            "plan" => $_POST['plan'],
            "title" => $_POST['title'],
            "description" => $_POST['description']
        ]
    ]);

    http_response_code(303);
    header("location:" . $checkout_session->url);
    exit();

} catch(\Stripe\Exception\ApiErrorException $e){

    header("location:../advertisement.php?error=paiement_error");
    exit();

}