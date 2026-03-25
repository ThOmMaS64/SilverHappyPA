<?php

    session_start();

    include("../includes/db.php");

    require_once __DIR__ . '/../stripe-php/init.php';

    \Stripe\Stripe::setApiKey('sk_test_51Szo1M2WiLfhQi4sPPN6NYJI4gecl8Au5rA0oxHK7grJ6H4u41IReSoXwXn0NqrtzqWW8yXOItSD7MvuuW0q0Sgt009dm7h1pa');

    $domain = 'http://localhost/ProjetAnnuel';

    $q = 'SELECT price FROM EVENT WHERE ID_EVENT = :id_event';
    $req = $bdd->prepare($q);
    $req->execute(['id_event' => $_POST['id_event']]);
    $price = $req->fetch(PDO::FETCH_COLUMN);

try{

    $checkout_session = \stripe\checkout\Session::create([
        "mode" => "payment",
        "success_url" => $domain . "/traitementsPHP/validInscriptionEvent.php?session_id={CHECKOUT_SESSION_ID}&id_event=" . $_POST['id_event'] . "&name=" . urlencode($_POST['name']),
        "cancel_url" => $domain . "/events.php?error=inscription_error",
        "line_items" => [
            [
                "quantity" => 1,
                "price_data" => [
                    "currency" => "eur",
                    "unit_amount" => $price*100,
                    "product_data" => [
                        "name" => $_POST['name']
                    ]
                ]
            ]
        ]
    ]);

    http_response_code(303);
    header("location:" . $checkout_session->url);
    exit();

} catch(\Stripe\Exception\ApiErrorException $e){

    header("location:../events.php?error=inscription_error");
    exit();

}