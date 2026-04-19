<?php

    session_start();

    include('../includes/db.php');

    $q = 'UPDATE CONSUMER SET tuto_seen = 1 WHERE ID_USER = :id';
    $req = $bdd->prepare($q);
    $req->execute(['id' => $_SESSION['id']]);

?>