<!DOCTYPE html>
<html lang="en">

    <?php 
        session_start();


        include("includes/translation.php");
        $pageTitle = trad("Ma publicité");

        include("includes/head.php");
        include("includes/headerMinimalist.php");

        $errorMessage = trad("Échec lors du paiement, veuillez réessayer.")

    ?>

    <body>
        <main style="padding-top:25px;">
            <div class="backgroundPlain">
                <div class="row ms-5 choiceImageContainer">
                    <div class="col-12">
                        <h2><?php echo trad("Choisissez votre pack de publicité :") ?></h2>
                        <div class="line mb-2"></div>
                        <p>Mettez en avant votre profil afin de maximiser votre visibilité auprès de l'ensemble de nos adhérents.</p>
                        <?php if (isset($_GET["error"])): ?>
                                <div class="alert alert-danger">
                                    <?php echo htmlspecialchars($errorMessage); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-4 pe-3 mt-3 text-center">
                            <a href="personalizeAdvertisement.php?plan=simple">
                                <img src="medias/images/pub1.png"  width="250px" class="choiceImage"> 
                            </a>
                        </div>
                        <div class="col-4 ps-3 mt-3 text-center">
                            <a href="personalizeAdvertisement.php?plan=double">
                                <img src="medias/images/pub2.png"  width="250px" class="choiceImage">
                            </a>
                        </div>
                        <div class="col-4 ps-3 mt-3 text-center">
                            <a href="personalizeAdvertisement.php?plan=ultra">
                                <img src="medias/images/pub3.png"  width="250px" class="choiceImage">
                            </a>
                        </div>
                </div>
            </div>
        </main>
        <?php include('includes/magnifyingLink.php'); ?>
    </body>
</html>