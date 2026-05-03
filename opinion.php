<?php 
    session_start();
    include('traitementsPHP/deconnexionAuto.php'); ?>
<!DOCTYPE html>
<html lang="en">

    <?php 

        include("includes/translation.php");

        $pageTitle = trad("Partager un avis");

        include("includes/head.php");
        include("includes/headerMinimalist.php");

        $dataJson = file_get_contents("http://localhost:8081/showDoneAndNotGradedServices?id=" . $_SESSION["id"]);

        if($dataJson){

            $response = json_decode($dataJson, true);

            $services = $response['services'] ?? [];

        }

        $errorMessages = [

            "system" => trad("Erreur lors de l'envoi de l'avis, veuillez réessayer"),

        ];

        $errorKey = $_GET["error"] ?? null;

        $errorMessage = $errorMessages[$errorKey] ?? null;



        $notif = [

            "grade_sent" => trad("Votre avis a correctement été partagé au prestataire concerné, merci d'aider Silver Happy et ses prestataires à s'améliorer de jour en jour."),

        ];

        $notifKey = $_GET["notif"] ?? null;

        $successMessage = $notif[$notifKey] ?? null;

    ?>

    <body>
        <main>
            <div class="backgroundPlain" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>">
                <div class="col-7">
                    <form class="row g-3 contactsForm" method="POST" action="http://localhost:8081/addGrade" novalidate>
                        <div class="row">
                            <div class="col-12">
                                <h3><?php echo trad("Partager un avis") ?></h3>
                                <div class="line mb-1"></div>
                                <p><?php echo trad("Vous avez profité d'une prestation de service proposée sur Silver Happy ? Faites en un retour en quelques minutes.") ?></p>
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
                        
                            <p><?php echo trad("Choisissez une prestation effectuée :") ?></p>
                            <div class="row mb-2 mt-2">
                                <div class="input-group">
                                    <select name="id_intervention" class="selectFilter">
                                        <option disabled selected><?php echo trad("Choisissez une prestation") ?></option>
                                        <?php foreach($services as $service): ?>
                                            <option value="<?= $service['id_intervention'] ?>"><?= htmlspecialchars($service['type']) ?><?= " - " ?><?= htmlspecialchars($service['service_provider_name']) ?><?= " " ?><?= htmlspecialchars($service['service_provider_surname']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6 mb-3 mt-3">
                                <label class="form-label"><?php echo trad("Note (sur 5)") ?></label>
                                <input type="number" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" step="0.5" max="5" min = "0" name="grade" required> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label"><?php echo trad("Argumentation de l'avis") ?></label>
                                <textarea class="form-control" name="description" maxlength="200"></textarea>                        
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?= $_SESSION['id'] ?>">
                        <div class="row">
                            <div class="col-12 mt-5">
                                <button type="submit" class="btn"><?php echo trad("Partager") ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-5">
                    <img src="medias/images/imageGrade2.jpg">
                </div>
            </div>
        </main>
        <?php include('includes/magnifyingLink.php'); ?>
    </body>
</html>