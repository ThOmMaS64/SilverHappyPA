<!DOCTYPE html>
<html lang="en">

    <?php 
        session_start();

        $pageTitle = "Choix de l'abonnement";

        include("includes/head.php"); 
        include("includes/headerMinimalist.php");

        $q = 'SELECT status FROM USER_ WHERE ID_USER = :id';
        $statement = $bdd->prepare($q);
        $result = $statement->execute([
            'id' => $_SESSION['id'],
        ]);    
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $status = $user['status'];     

        if($status == -1){
            $standardOrRenewal = "standard";
        }else{
            $standardOrRenewal = "renewal";
        }

        $errorMessage = "Échec lors du paiement, veuillez réessayer."

    ?>

    <body>
        <main style="padding-top:25px;">
            <div class="backgroundPlain">
                <div class="row ms-5 choiceImageContainer">
                    <div class="col-12">
                        <h2>Choisissez votre abonnement :</h2>
                        <div class="line mb-2"></div>
                        <?php if (isset($_GET["error"])): ?>
                                <div class="alert alert-danger">
                                    <?php echo htmlspecialchars($errorMessage); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <div class="col-6 pe-3 text-center">
                        <p>Abonnemment <strong>mensuel</strong> (4€ le premier mois puis 3€/mois)</p>
                        <a href="traitementsPHP/createStripeSession.php?plan=monthly_<?php echo $standardOrRenewal ?>">
                            <img src="medias/images/mensualPayment.jpg"  width="220px" class="choiceImage"> 
                        </a>
                    </div>
                    <div class="col-6 ps-3 text-center">
                        <p>Abonnemment <strong>annuel</strong> (40€ la première année puis 35€/mois)</p>
                        <a href="traitementsPHP/createStripeSession.php?plan=annual_<?php echo $standardOrRenewal ?>">
                            <img src="medias/images/annualPayment.jpg"  width="220px" class="choiceImage">
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>