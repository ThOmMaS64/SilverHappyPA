<?php

session_start();

session_unset(); 

session_destroy();

if(isset($_GET['notif']) && $_GET['notif'] = "account_suppression"){

    header('location: index.php?notif=account_suppression');
    exit();

}

header('location: index.php');

exit();
?>