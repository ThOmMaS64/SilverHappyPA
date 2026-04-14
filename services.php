<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();

    include("includes/translation.php");

    $pageTitle = trad("Prestations");

    include("includes/head.php");
    include("includes/header.php");

    if(isset($_POST['saveUnsaveService'])){

        $responseSaveUnsave = file_get_contents("http://localhost:8081/saveUnsaveService?id=".$_SESSION['id']."&ID_SERVICE=".$_POST['saveUnsaveService']);

        if($responseSaveUnsave == "save_success"){

            $successMessage = "Prestation enregistrée, vous pouvez dès à présent la retrouver sur votre profil";

        }elseif($responseSaveUnsave == "unsave_success"){

            $successMessage = "Prestation supprimée de vos enregistrements.";

        }

    }

    if(isset($_SESSION['id'])){
            $dataJson = file_get_contents("http://localhost:8081/showDefaultServicesPage?id=". $_SESSION['id']);
            

            $distinctTypes = [];
            $serviceList = [];

            if($dataJson){

                $response = json_decode($dataJson, true);

                if(isset($response['error']) && $response['error'] != ""){

                    $errorMessage = $response['error'];

                }else{

                    $distinctTypes = $response['types'];
                    $serviceList = $response['services'];

                }

            }
        }else{
            $dataJson = file_get_contents("http://localhost:8081/showDefaultServicesPage");
            

            $distinctTypes = [];
            $serviceList = [];

            if($dataJson){

                $response = json_decode($dataJson, true);

                if(isset($response['error']) && $response['error'] != ""){

                    $errorMessage = $response['error'];

                }else{

                    $distinctTypes = $response['types'];
                    $serviceList = $response['services'];

                }

            }
        }

        if(isset($_GET['research']) || isset($_GET['filter']) || isset($_GET['sort'])){

            if(isset($_GET['research'])){
                $research = urlencode($_GET['research']);
            }else{
                $research = "";
            }

            if(isset($_GET['filter'])){
                $filter = urlencode($_GET['filter']);
            }else{
                $filter = "";
            }

            if(isset($_GET['sort'])){
                $sort = urlencode($_GET['sort']);
            }else{
                $sort = "";
            }

            $id = isset($_SESSION['id']) ? $_SESSION['id'] : "";

            $dataJson = file_get_contents("http://localhost:8081/showPersonalizedServicesPage?id=".$id."&research=".$research."&filter=".$filter."&sort=".$sort);

            if($dataJson){

                $response = json_decode($dataJson, true);

                if(isset($response['error']) && $response['error'] != ""){

                    $errorMessage = $response['error'];

                }else{

                    $serviceList = $response['services'];

                }

            }

        }

        $notif = [

            "paiement_success" => "Inscription à la prestation validée. Vous retrouverez votre facture sur votre profil.",

        ];

        $notifKey = $_GET["notif"] ?? null;

        $successMessage = $notif[$notifKey] ?? null;

        $error = [

            "inscription_error" => "Inscription à la prestation échouée, veuillez réessayer.",
            "invoice_error" => "Erreur lors de la génération de la facture, veuillez contacter nos services via la page Contacts. Vous êtes en revanche bien inscrit à la prestation."

        ];

        $errorKey = $_GET["error"] ?? null;

        $errorMessage = $error[$errorKey] ?? null;
    ?>
    <body>
        <main>
            <div class="backgroundPlain" style="background-color:#f2f6fa; align-items:flex-start;">
                <div class="col-3 ps-4 pe-4" style="color:white; background-color:rgb(62, 134, 189); min-height: 120vh; padding-top:150px; clip-path: polygon(0% 0%, 100% 0%, 100% 90%, 0% 100%);<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                    <h3><?php echo trad("Prestations") ?></h3>
                    <div class="line"></div>
                    <p><?php echo trad("Cette page vous donne accès aux prestations proposées par les associés de Silver Happy.") ?></p>

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

                    <form method="GET" action="">
                        <p><strong><?php echo trad("Rechercher une prestation :") ?></strong></p>
                        <div class="row mb-5">
                            <div class="input-group">
                                <input value="<?php if(isset($_GET['research'])){ echo htmlspecialchars($_GET['research']); }else{ echo ""; } ?>" class="form-control" name="research" placeholder="<?php if(isset($_GET['research']) && $_GET['research'] != ""){ echo $_GET['research']; }else{ ?><?php echo trad("Tapez votre recherche") ?> <?php } ?>" aria-label="Search">
                                <button class="searchButton" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <p><strong><?php echo trad("Filtrer les prestations :") ?></strong></p>
                        <div class="row mb-5">
                            <div class="input-group">
                                <select name="filter" class="selectFilter" onchange="this.form.submit()">
                                    <option disabled selected><?php echo trad("Choisissez un thème") ?></option>
                                    <?php foreach($distinctTypes as $type): ?>
                                        <option value="<?= htmlspecialchars($type) ?>" <?php if(isset($_GET['filter']) && $_GET['filter'] == $type){ echo 'selected'; } ?> ><?= htmlspecialchars(trad($type)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <p><strong><?php echo trad("Trier les prestations :") ?></strong></p>
                        <div class="row">
                            <div class="input-group">
                                <select name="sort" class="selectFilter" onchange="this.form.submit()">
                                    <option value="" disabled selected><?php echo trad("Choisissez une méthode de tri") ?></option>
                                        <option value="1" <?php if(isset($_GET['sort']) && $_GET['sort'] == "1"){ echo 'selected'; } ?>><?php echo trad("Coût croissant") ?></option>
                                        <option value="2" <?php if(isset($_GET['sort']) && $_GET['sort'] == "2"){ echo 'selected'; } ?>><?php echo trad("Coût décroissant") ?></option>
                                        <option value="3" <?php if(isset($_GET['sort']) && $_GET['sort'] == "3"){ echo 'selected'; } ?>><?php echo trad("Par préférence") ?></option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="lateralAffichage col-9" style="background-color:#f2f6fa;padding-top:140px; padding-bottom:90px;<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>;">
                    <?php if(!empty($serviceList)){ ?>
                        <?php foreach($serviceList as $service){ ?>

                            <div class="showEvent" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">

                                <div class="row">
                                    <div class="col-10">
                                        <h5><?php echo htmlspecialchars(trad($service['type'])) ?></h5>
                                        <div class="line"></div>
                                        <p><?php echo htmlspecialchars($service['place']) ?></p>
                                    </div>
                                    <div class="col-2">
                                        <form method="POST" action="">
                                            <input type="hidden" name="saveUnsaveService" value="<?php echo htmlspecialchars($service['ID_SERVICE']); ?>"></input>
                                            <?php if(!$service['is_saved']){ ?>
                                                <button type="<?php echo isset($_SESSION['id']) ? 'submit' : 'button'; ?>" class="saveUnsaveButton" <?php if(!isset($_SESSION['id'])) { ?> onclick="window.location.href='connexion.php?notif=need_connexion'" <?php } ?>>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">
                                                        <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z"/>
                                                    </svg>
                                                </button>
                                            <?php }else{ ?>
                                                <button class="saveUnsaveButton">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                                    </svg>
                                                </button>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>

                                <p><?php echo htmlspecialchars(tradByAPI($service['description'])) ?></p>

                                <?php if($service['pricing_type'] == 'fixed'){ ?>
                                    <p><small><?php echo $service['cost'] ?> <?php echo "€" ?></small></p>
                                <?php }else{ ?>
                                    <p><small><?php echo trad("Prix sur devis") ?></small></p>
                                <?php } ?>

                            <?php if($service['requires_date'] && !empty($service['slots'])){ ?>
                                <p class="mt-5"><strong><?php echo trad("Choisissez un créneau :") ?></strong></p>
                                
                                <form method="GET" action="<?php echo ($service['pricing_type'] == 'fixed') ? 'traitementsPHP/checkoutServices.php' : 'communications.php'; ?>">
                                    <?php foreach($service['slots'] as $slot){ ?>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <input type="radio" name="slot" value="<?php echo $slot['id_service_slot']; ?>" id="slot_<?php echo $slot['id_service_slot']; ?>" required>
                                            <label for="slot_<?php echo $slot['id_service_slot']; ?>">
                                                <?php echo date('d/m/Y H:i', strtotime($slot['start_time'])) ?> - <?php echo date('H:i', strtotime($slot['end_time'])) ?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                    
                                    <input type="hidden" name="id_service" value="<?php echo $service['ID_SERVICE']; ?>">

                                    <button type="submit" class="btn btn-primary mt-3" style="width:80%;color:black;" <?php if(!isset($_SESSION['id'])) { ?> onclick="window.location.href='connexion.php?notif=need_connexion'; return false;" <?php } ?>>
                                        <?php echo ($service['pricing_type'] == 'fixed') ? trad("Réserver le créneau sélectionné") : trad("Demander un devis"); ?>
                                    </button>
                                </form>
                            <?php }elseif($service['requires_date'] && empty($service['slots'])){ ?>

                                <p><?= trad("Aucun créneau disponible"); ?></p>

                            <?php }elseif(!$service['requires_date']){ ?>
                                <?php if($service['pricing_type'] == 'fixed'){ ?>
                                <a href="traitementsPHP/checkoutServices.php?id_service=<?php echo $service['ID_SERVICE'] ?>" <?php if(!isset($_SESSION['id'])) { ?> onclick="window.location.href='connexion.php?notif=need_connexion'; return false;" <?php } ?>>
                                    <button class="btn" style="width:100%;color:black;" <?php if(!isset($_SESSION['id'])) { ?> onclick="window.location.href='connexion.php?notif=need_connexion'" <?php } ?>>
                                        <?php echo trad("Réserver") ?>
                                    </button>
                                </a>
                                <?php }else{ ?>
                                <a href="communications.php?id_service=<?php echo $service['ID_SERVICE'] ?>" <?php if(!isset($_SESSION['id'])) { ?> onclick="window.location.href='connexion.php?notif=need_connexion'; return false;" <?php } ?>>
                                    <button class="btn" style="width:100%;color:black;" <?php if(!isset($_SESSION['id'])) { ?> onclick="window.location.href='connexion.php?notif=need_connexion'; return false;" <?php } ?>>
                                        <?php echo trad("Demander un devis") ?>
                                    </button>
                                </a>
                                <?php } ?>
                            <?php } ?>
                            </div>

                        <?php } ?>
                    <?php }else{ ?>

                        <p style="justify-self:center;padding-top:150px;"><?php echo trad("Aucune prestation n'a été partagée pour le moment.") ?></p>

                    <?php } ?>
                </div>
            </div>
        </main>
        <?php include("includes/footer.php");
        include('includes/magnifyingLink.php'); ?>
    </body>
</html>