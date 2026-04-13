<?php

    session_start();

    include("../includes/db.php");

    require_once __DIR__ . '/../stripe-php/init.php';

    \Stripe\Stripe::setApiKey('sk_test_51Szo1M2WiLfhQi4sPPN6NYJI4gecl8Au5rA0oxHK7grJ6H4u41IReSoXwXn0NqrtzqWW8yXOItSD7MvuuW0q0Sgt009dm7h1pa');

    $domain = 'http://localhost/ProjetAnnuel';

    $q = 'SELECT cost, type FROM SERVICE WHERE ID_SERVICE = :id_service';
    $req = $bdd->prepare($q);
    $req->execute(['id_service' => $_GET['id_service']]);
    $infos = $req->fetch(PDO::FETCH_ASSOC);

try{

    $checkout_session = \stripe\checkout\Session::create([
        "mode" => "payment",
        "success_url" => $domain . "/traitementsPHP/validServicePaiement.php?session_id={CHECKOUT_SESSION_ID}&id_service=" . $_GET['id_service'] . "&type=" . urlencode($infos['type']) . (!empty($_GET['slot']) ? "&id_service_slot=" . $_GET['slot'] : ""),
        "cancel_url" => $domain . "/services.php?error=inscription_error",
        "line_items" => [
            [
                "quantity" => 1,
                "price_data" => [
                    "currency" => "eur",
                    "unit_amount" => $infos['cost']*100,
                    "product_data" => [
                        "name" => $infos['type']
                    ]
                ]
            ]
        ]
    ]);

    http_response_code(303);
    header("location:" . $checkout_session->url);
    exit();

} catch(\Stripe\Exception\ApiErrorException $e){

    header("location:../services.php?error=inscription_error");
    exit();

}