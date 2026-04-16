<?php

    session_start();

    include("../includes/db.php");

    require_once __DIR__ . '/../stripe-php/init.php';

    \Stripe\Stripe::setApiKey('sk_test_51Szo1M2WiLfhQi4sPPN6NYJI4gecl8Au5rA0oxHK7grJ6H4u41IReSoXwXn0NqrtzqWW8yXOItSD7MvuuW0q0Sgt009dm7h1pa');

    $domain = 'http://localhost/ProjetAnnuel';

    $q = 'SELECT SUM(QUOTE.amount) as total_unpaid_quote FROM QUOTE INNER JOIN CONSUMER ON QUOTE.ID_CONSUMER = CONSUMER.ID_CONSUMER WHERE QUOTE.status = 1 AND CONSUMER.ID_USER = :id';
    $req = $bdd->prepare($q);
    $req->execute(['id' => $_SESSION['id']]);
    $result = $req->fetch(PDO::FETCH_ASSOC);

    $totalAmount = $result['total_unpaid_quote'];

try{

    $checkout_session = \stripe\checkout\Session::create([
        "mode" => "payment",
        "success_url" => $domain . "/traitementsPHP/validPaiementQuote.php?session_id={CHECKOUT_SESSION_ID}",
        "cancel_url" => $domain . "/index.php?notif=quote_paiement_error",
        "line_items" => [
            [
                "quantity" => 1,
                "price_data" => [
                    "currency" => "eur",
                    "unit_amount" => $totalAmount*100,
                    "product_data" => [
                        "name" => "Devis"
                    ]
                ]
            ]
        ]
    ]);

    http_response_code(303);
    header("location:" . $checkout_session->url);
    exit();

} catch(\Stripe\Exception\ApiErrorException $e){

    header("location:../index.php?notif=quote_paiement_error");
    exit();

}