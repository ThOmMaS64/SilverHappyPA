<?php 
    session_start();

    $stripeAccountId = null;

    if(isset($_SESSION['id_service_provider'])){

        $dataStripe = file_get_contents("http://localhost:8081/getStripeAccountId?id_service_provider=" . $_SESSION['id_service_provider']);

        if($dataStripe){

            $decoded = json_decode($dataStripe, true);
            $stripeAccountId = $decoded['stripe_account_id'] ?? null;

        }

    }

    include('traitementsPHP/deconnexionAuto.php'); ?>
<!DOCTYPE html>
<html lang="en">

    <?php 

        include("includes/translation.php");

        $pageTitle = trad("Tableau de bord");

        include("includes/head.php");
        include("includes/header.php");

        if(isset($_SESSION['id'])){

            $dataJson = file_get_contents("http://localhost:8081/showServices?id_provider=" . $_SESSION['id_service_provider']);

            $data = json_decode($dataJson, true);
            $distinctServices = $data['services'];
        
            $providerServicesJson = file_get_contents("http://localhost:8081/showProviderServices?id_provider=" .  $_SESSION['id_service_provider']);
            $data = json_decode($providerServicesJson, true);
            $providerServices = $data['services'];
                        
        }else{
            
            header("location:connexion.php?need_connexion");
            return;

        }

        if(isset($_GET['chosenService'])){

            $chosenService = urlencode($_GET['chosenService']);

            $dataJson = file_get_contents("http://localhost:8081/showNeededDocuments?service=" . $chosenService);

            $data = json_decode($dataJson, true);
            $neededDocuments = (is_array($data) && isset($data['documents'])) ? $data['documents'] : [];

            $dataServiceInfo = file_get_contents("http://localhost:8081/showServiceInfo?service=" . $chosenService);
            $serviceInfo = json_decode($dataServiceInfo, true);
            $isOnline = $serviceInfo['is_online'] ?? false;
            $isAtConsumerHome = $serviceInfo['is_at_consumer_home'] ?? false;

        }

    ?>
    <?php

        $errorMessages = [

            "add_error" => trad("Erreur lors de l'ajout du services à votre profil, veuillez réessayer."),
            "delete_error" => trad("Erreur lors de la suppression du créneau, veuillez vous assurer qu'il n'est pas déjà réservé avant de réessayer."),
            "delete_service_error" => trad("Erreur lors de la suppression du service, veuillez réessayer."),
            "update_service_error" => trad("Erreur lors de la modification du service, veuillez réessayer."),
            "add_error" => trad("L'ajout du créneau a échoué."),

        ];

        $errorKey = $_GET["error"] ?? null;

        $errorMessage = $errorMessages[$errorKey] ?? null;



        $notif = [

            "documents_sent" => trad("Demande envoyée, vos documents seront prochainement étudiés par nos équipes !"),
            "new_service" => trad("Service ajouté à votre liste de prestations."),
            "service_deleted" => trad("Service supprimé de vos prestations."),
            "service_updated" => trad("Service mis à jour avec succès."),
            "slot_added" => trad("Créneau ajouté avec succès."),

        ];

        $notifKey = $_GET["notif"] ?? null;

        $successMessage = $notif[$notifKey] ?? null;


        $providerMensualAmountJson = file_get_contents("http://localhost:8081/getServiceProviderMensualAmount?id_service_provider=" .  $_SESSION['id_service_provider']);
        $data = json_decode($providerMensualAmountJson, true);
        $providerMensualAmount = $data['amount'];
        $providerMensualServicesNb = $data['nb_services_provided'];
    ?>

    <body>
        <main>
            <?php if(isset($_SESSION['id_service_provider']) && empty($stripeAccountId)): ?>
                <div class="backgroundPlain" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
                    <div class="backgroundForm col-4">
                        <h4>Configurez votre compte de paiement</h4>
                        <div class="line mb-4"></div>
                        <p>Pour accéder à votre tableau de bord et recevoir vos paiements, veuillez renseigner votre identifiant de compte Stripe.</p>
                        <form method="POST" action="http://localhost:8081/saveStripeAccountId">
                            <input type="hidden" name="id_service_provider" value="<?= $_SESSION['id_service_provider'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Identifiant de compte Stripe (acct_...)</label>
                                <input type="text" name="stripe_account_id" class="form-control" placeholder="acct_XXXXXXXXXXXXXXXX" required>
                            </div>
                            <button type="submit" class="btn">
                                Enregistrer
                            </button>
                        </form>
                    </div>
                </div>

            <?php else: ?>

                <div class="backgroundPlain" style="background-color:#f2f6fa; align-items:flex-start;">
                    <div class="col-3 ps-4 pe-4" style="color:white; background-color:rgb(62, 134, 189); min-height: 120vh; padding-top:150px; clip-path: polygon(0% 0%, 100% 0%, 100% 90%, 0% 100%);<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                        <h3><?php echo trad("Tableau de bord") ?></h3>
                        <div class="line"></div>
                        <p><?php echo trad("Cette page vous donne accès à votre tableau de bord, vous pouvez y gérer vos services et proposer des conseils.") ?></p>

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

                            <p class="mt-5"><strong>Revenus du mois en cours :</strong></p>

                            <?= $providerMensualAmount ?><?= " € en " ?><?= $providerMensualServicesNb ?><?php if($providerMensualServicesNb > 1){ echo " prestations."; }else{ echo " prestation."; } ?>
                        </div>
                    </div>
                    <div class="lateralAffichage col-9" style="background-color:#f2f6fa;padding-top:140px; padding-bottom:90px;<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>;">
                        
                        <h5 class="mt-3">Proposez un nouveau service</h5>
                        <div class="line mb-4"></div>

                        <?php 
                        
                        $documentOrNo = 1;
                        
                        if(isset($_GET['chosenService']) && empty($neededDocuments)) {

                            $documentOrNo = 0;

                        }
                        
                        ?>
                        
                        <form action="http://localhost:8081/addServiceProviderDocuments?documentOrNo=<?= $documentOrNo ?>" method="POST" enctype="multipart/form-data">

                            <input type="hidden" name="id_service_provider" value="<?= $_SESSION['id_service_provider'] ?? '' ?>">
                            <input type="hidden" name="service_type" value="<?= htmlspecialchars($_GET['chosenService'] ?? '') ?>">

                            <div class="col-6">
                                <div class="row mb-5">
                                    <div class="input-group">
                                        <label class="mb-1">Choisissez un service parmi cette sélection :</label>
                                        <select name="chosenService" class="selectFilter" onchange="window.location.href='?chosenService='+this.value">
                                                <option disabled selected><?php echo trad("Choisissez un service") ?></option>
                                            <?php foreach($distinctServices as $service): ?>
                                                <option value="<?= htmlspecialchars($service) ?>" <?php if(isset($_GET['chosenService']) && $_GET['chosenService'] == $service){ echo 'selected'; } ?> ><?= htmlspecialchars(trad($service)) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                            
                                <?php if(!empty($neededDocuments)){ ?>
                                    <div class="row">

                                        <p>Documents nécessaires :</p>
                                        <?php foreach($neededDocuments as $document): ?>

                                            <?php $docNameInput = "doc_" . str_replace(' ', '_', strtolower($document)); ?>

                                            <div class="mb-3">
                                                <label class="form-label"><?= htmlspecialchars(trad($document)) ?> :</label>
                                                <input type="file" name="<?= $docNameInput ?>" class="form-control" required>
                                                
                                            </div>

                                        <?php endforeach; ?>
                                        
                                    </div>
                                <?php } elseif(isset($_GET['chosenService'])) { ?>
                                
                                    <p>Aucun document requis pour ce service.</p>

                                <?php } ?>

                                <div class="mb-3">
                                    <label>Choisissez le type de la tarification :</label>
                                    <select name="pricing_type" id="pricing_type" class="form-control" onchange="document.getElementById('cost_div').style.display = this.value === 'fixed' ? 'block' : 'none';">
                                        <option value="fixed">Prix fixe prédéfinis</option>
                                        <option value="quote">Prix sur devis</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="cost_div">
                                    <label>Prix (en €) :</label>
                                    <input type="number" step="0.01" name="cost" class="form-control">
                                </div>

                                <?php if(isset($_GET['chosenService']) && !$isOnline && !$isAtConsumerHome): ?>
                                    <div class="mb-3" id="address_div">
                                        <label class="mb-1">Adresse de réalisation du service :</label>
                                        <div class="row">
                                            <div class="col-3 mb-2">
                                                <input type="number" name="nb_street" class="form-control" placeholder="Numéro de rue">
                                            </div>
                                            <div class="col-9 mb-2">
                                                <input type="text" name="street" class="form-control" placeholder="Rue">
                                            </div>
                                            <div class="col-6 mb-2">
                                                <input type="text" name="city" class="form-control" placeholder="Ville">
                                            </div>
                                            <div class="col-6 mb-2">
                                                <input type="text" name="postal_code" class="form-control" placeholder="Code postal">
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if(isset($neededDocuments)): ?>
                                    <button type="submit" class="btn mt-4">Demander à réaliser ce service</button>
                                <?php endif; ?>
                            </div>
                        </form>

                        <h5 class="mt-5"><?php echo trad("Ajouter une disponibilité"); ?></h5>
                            <div class="line mb-4"></div>

                            <form action="http://localhost:8081/saveServiceSlots" method="POST" class="col-8">
                                <input type="hidden" name="id_service_provider" value="<?= $_SESSION['id_service_provider'] ?>">

                                <div class="mb-3">
                                    <label class="form-label">Service concerné :</label>
                                    <select name="id_service" class="selectFilter w-100" required>
                                        <option value="" disabled selected><?php echo trad("Choisissez un service") ?></option>
                                        <?php foreach($providerServices as $provService): ?>

                                            <?php if($provService['pricing_type'] == 'fixed'){ ?>

                                                <option value="<?= htmlspecialchars($provService['id']) ?>">
                                                    <?= htmlspecialchars(trad($provService['type'])) ?>
                                                </option>

                                            <?php } ?>

                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Date :</label>
                                    <input type="date" name="date" class="form-control" required>
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Heure de début :</label>
                                        <input type="time" name="start_time" class="form-control" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Heure de fin :</label>
                                        <input type="time" name="end_time" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" name="is_recurring" id="is_recurring">
                                    <label class="form-check-label" for="is_recurring">
                                        Créneau hebdomadaire
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary" style="background-color: rgb(62, 134, 189); border:none;">
                                    Ajouter
                                </button>
                            </form>

                            <h5 class="mt-5"><?php echo trad("Supprimer un créneau"); ?></h5>
                            <div class="line mb-4"></div>

                            <form action="http://localhost:8081/deleteServiceSlot" method="POST" class="col-6">
                                <input type="hidden" name="id_service_provider" value="<?= $_SESSION['id_service_provider'] ?>">

                                <div class="mb-3">
                                    <label class="form-label">Service concerné :</label>
                                    <select name="id_service" class="selectFilter w-100" required>
                                        <option value="" disabled selected><?php echo trad("Choisissez un service") ?></option>
                                        <?php foreach($providerServices as $provService): ?>
                                            <option value="<?= htmlspecialchars($provService['id']) ?>">
                                                <?= htmlspecialchars(trad($provService['type'])) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Date :</label>
                                    <input type="date" name="date" class="form-control" required>
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Heure de début :</label>
                                        <input type="time" name="start_time" class="form-control" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Heure de fin :</label>
                                        <input type="time" name="end_time" class="form-control" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-danger" style="border:none;">
                                    Supprimer le créneau
                                </button>
                            </form>

                            <h5 class="mt-5"><?php echo trad("Supprimer un service de vos prestations proposées"); ?></h5>
                            <div class="line mb-4"></div>

                            <form action="http://localhost:8081/deleteServiceFromOffers" method="POST" class="col-8">
                                <input type="hidden" name="id_service_provider" value="<?= $_SESSION['id_service_provider'] ?>">

                                <div class="mb-3">
                                    <label class="form-label">Service concerné :</label>
                                    <select name="id_service" class="selectFilter w-100" required>
                                        <option value="" disabled selected><?php echo trad("Choisissez un service") ?></option>
                                        <?php foreach($providerServices as $provService): ?>

                                                <option value="<?= htmlspecialchars($provService['id']) ?>">
                                                    <?= htmlspecialchars(trad($provService['type'])) ?>
                                                </option>

                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary" style="background-color: rgb(62, 134, 189); border:none;">
                                    Supprimer
                                </button>
                            </form>

                            <h5 class="mt-5"><?php echo trad("Modifier le prix ou le type de tarification d'un service"); ?></h5>
                            <div class="line mb-4"></div>

                            <form action="http://localhost:8081/updateServiceOffer" method="POST" class="col-8">
                                <input type="hidden" name="id_service_provider" value="<?= $_SESSION['id_service_provider'] ?>">

                                <div class="mb-3">
                                    <label class="form-label">Service concerné :</label>
                                    <select name="id_service" class="selectFilter w-100" required>
                                        <option value="" disabled selected><?php echo trad("Choisissez un service") ?></option>
                                        <?php foreach($providerServices as $provService): ?>
                                            <option value="<?= htmlspecialchars($provService['id']) ?>">
                                                <?= htmlspecialchars(trad($provService['type'])) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Type de tarification :</label>
                                    <select name="pricing_type" class="form-control" onchange="document.getElementById('cost_div_update').style.display = this.value === 'fixed' ? 'block' : 'none';">
                                        <option value="fixed">Prix fixe prédéfinis</option>
                                        <option value="quote">Prix sur devis</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="cost_div_update">
                                    <label>Nouveau prix (en €) :</label>
                                    <input type="number" step="0.01" name="cost" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary" style="background-color: rgb(62, 134, 189); border:none;">
                                    Modifier
                                </button>
                            </form>
                    </div>
                </div>
            
            <?php endif; ?>

        </main>
        <?php include("includes/footer.php");
        include('includes/magnifyingLink.php'); 
        include('includes/audioLink.php'); ?>

        <audio id="audio" src="audios/dashboard.m4a"></audio>

        <script>

            document.getElementById('audioButton').addEventListener('click', function(e){
                e.preventDefault();
                document.getElementById('audio').play();

            })

        </script>
    </body>
</html>