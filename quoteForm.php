<!DOCTYPE html>
<html lang="en">

    <?php 
        session_start();

        include("includes/translation.php");

        $pageTitle = trad("Proposer un devis");

        include("includes/head.php");
        include("includes/header.php");

        if(isset($_GET['id_discussion'])){

        $dataJson = file_get_contents("http://localhost:8081/getServicesForQuote?id_discussion=". $_GET['id_discussion']);

        if($dataJson){

            $servicesList = json_decode($dataJson, true);

        }

    }

    ?>

    <body>
        <main>
            <div class="backgroundPlain">
                <div class="col-4 backgroundForm mt-5">
                    <form class="row g-3 needs-validation" method="POST" action="http://localhost:8081/sendQuote" novalidate>
                        <div class="row">
                            <div class="col-12">
                                <h3><?php echo trad("Proposez un devis") ?></h3>
                                <div class="line mb-4"></div>
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
                                <label class="form-label"><strong><?php echo trad("Prestation") ?></strong></label>
                                <?php if(empty($servicesList)){ ?>
                                    <div class="alert alert-danger">
                                        <?php echo trad("Aucune prestation en attente de devis.") ?>
                                    </div>
                                <?php }else{ ?>
                                    <select class="form-select <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="id_service" required>
                                        <option value="" disabled selected><?php echo trad("Choisissez la prestation") ?></option>
                                        <?php foreach($servicesList as $service): ?>
                                            <option value="<?php echo htmlspecialchars($service['id_service']); ?>">
                                                <?php echo htmlspecialchars(trad($service['type'])); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php } ?>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label"><?php echo trad("Prix (HT et sans compter la commission)") ?></label>
                                <input type="number" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="prix" required> 
                            </div>

                            <p class="mt-1">Pour la date de la préstation, veuillez remplir un seul des trois champs ci-dessous :</p>

                            <div class="col-12 mb-2">
                                <label class="form-label"><?php echo trad("Date unique :") ?></label>
                                <input type="date" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="dateUnique" required> 
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label"><?php echo trad("Période :") ?></label>
                                <div class="row">  
                                    <div class="col-6">
                                        <input type="date" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="dateDebut" required> 
                                    </div>
                                    <div class="col-6">
                                        <input type="date" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="dateFin" required> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label"><?php echo trad("Comme spécifie ci-dessous :") ?></label>
                                <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="datePerso" required> 
                            </div>

                            <input type="hidden" name ="id" value="<?=htmlspecialchars($_SESSION['id']) ?>">
                            <input type="hidden" name ="id_discussion" value="<?=htmlspecialchars($_GET['id_discussion']) ?>">

                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn"><?php echo trad("Envoyer le devis") ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <?php include('includes/magnifyingLink.php'); ?>
        <script src="jsFunctions/showPassword.js"></script>
    </body>
</html>