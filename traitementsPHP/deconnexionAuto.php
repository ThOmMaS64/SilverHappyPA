<?php 
    if(isset($_SESSION['id'])){
        if(isset($_SESSION['lastAction'])){
            if(time() - $_SESSION['lastAction'] > 1800){
                header('location:deconnexion.php');
                exit();
            }else{
            $_SESSION['lastAction'] = time();
        }
        }else{
            $_SESSION['lastAction'] = time();
        }
    }   
?>