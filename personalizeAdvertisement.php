<?php 
    session_start();
    include('traitementsPHP/deconnexionAuto.php'); ?>
<!DOCTYPE html>
<html lang="en">

    <?php 

        include("includes/translation.php");

        $pageTitle = trad("Ma publicité");

        include("includes/head.php");
        include("includes/header.php");

    ?>

    <body>
        <main>
            <div class="backgroundPlain" style="min-height:130vh;">
                <div class="col-8 backgroundForm" style="margin-top:-150px;">
                    <form class="row g-3 needs-validation" method="POST" action="traitementsPHP/checkoutAdvertisement.php" novalidate>
                        <div class="row">
                            <div class="col-12">
                                <h3><?php echo trad("Personnalisez votre publicité") ?></h3>
                                <div class="line mb-2"></div>
                            </div>

                            <div class="col-12">
                                <?php if (isset($errorMessage)): ?>
                                    <div class="alert alert-danger">
                                        <?php echo htmlspecialchars($errorMessage); ?>
                                    </div>
                                <?php elseif(isset($successMessage)): ?>
                                    <div class="alert alert-success">
                                        <?php echo htmlspecialchars($successMessage); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label"><?php echo trad("Entête") ?></label>
                                <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="title" required> 
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label"><?php echo trad("Description") ?></label>
                                <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="description" required> 
                            </div>

                            <input type="hidden" name="plan" value="<?= $_GET['plan']; ?>"> 
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn"><?php echo trad("Valider") ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <?php 
        include('includes/magnifyingLink.php'); ?>
        <script src="jsFunctions/showPassword.js"></script>
    </body>
</html>