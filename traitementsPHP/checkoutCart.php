<?php

    session_start();

    include("../includes/db.php");

    require_once __DIR__ . '/../stripe-php/init.php';

    \Stripe\Stripe::setApiKey('sk_test_51Szo1M2WiLfhQi4sPPN6NYJI4gecl8Au5rA0oxHK7grJ6H4u41IReSoXwXn0NqrtzqWW8yXOItSD7MvuuW0q0Sgt009dm7h1pa');

    $domain = 'http://localhost/ProjetAnnuel';

    $q = 'SELECT ID_CONSUMER FROM CONSUMER WHERE ID_USER = :id';
    $req = $bdd->prepare($q);
    $req->execute(['id' => $_SESSION['id']]);
    $consumer = $req->fetch();
    $idConsumer = $consumer['ID_CONSUMER'];

    $q = 'SELECT ID_SHOP_ORDER FROM SHOP_ORDER WHERE ID_CONSUMER = :id_consumer AND status = 0';
    $req = $bdd->prepare($q);
    $req->execute(['id_consumer' => $idConsumer]);
    $shopOrder = $req->fetch();
    $idShopOrder = $shopOrder['ID_SHOP_ORDER'];

    $q = 'SELECT PRODUCT.name, PRODUCT.price, ORDER_LINE.quantity FROM PRODUCT INNER JOIN ORDER_LINE ON PRODUCT.ID_PRODUCT = ORDER_LINE.ID_PRODUCT WHERE ORDER_LINE.ID_SHOP_ORDER = :id_shop_order';
    $req = $bdd->prepare($q);
    $req->execute(['id_shop_order' => $idShopOrder]);
    $cartItems = $req->fetchAll();

    $line_items = [];
    foreach($cartItems as $item){
        $line_items[] = [
            "quantity" => $item['quantity'],
            "price_data" => [
                "currency" => "eur",
                "unit_amount" => $item['price'] * 100,
                "product_data" => [
                    "name" => $item['name']
                ]
            ]
        ];
    }

try{

    $checkout_session = \stripe\checkout\Session::create([
        "mode" => "payment",
        "success_url" => $domain . "/traitementsPHP/validCartPaiement.php?session_id={CHECKOUT_SESSION_ID}",
        "cancel_url" => $domain . "/events.php?error=inscription_error",
        "line_items" => $line_items
        
    ]);

    http_response_code(303);
    header("location:" . $checkout_session->url);
    exit();

} catch(\Stripe\Exception\ApiErrorException $e){

    header("location:../cart.php?error=paiement_error");
    exit();

}