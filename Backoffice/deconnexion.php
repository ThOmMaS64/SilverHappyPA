<?php

session_start();

include("../includes/db.php");

$q = 'UPDATE USER_ SET connected = 0 WHERE ID_USER = :id';
$statement = $bdd->prepare($q);
$statement->execute(['id' => $_SESSION['id']]);

session_unset(); 

session_destroy();

if(isset($_GET['notif']) && $_GET['notif'] == "account_suppression"){

    header('location: index.php?notif=account_suppression');
    exit();

}

header('location: index.php');

exit();
?>