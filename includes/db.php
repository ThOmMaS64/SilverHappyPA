<?php

    $user = "root";
    $password = "root";

    try {

        $bdd = new PDO(
            'mysql:host=localhost;dbname=silverhappy3;charset=utf8',
            $user,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        //echo "Connexion à la base de données réussie";

    } catch (Exception $e) {

        die("Erreur lors de la tentative de connexion à la base de données : " . $e->getMessage());

    }

?>