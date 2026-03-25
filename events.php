<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();

    include("includes/translation.php");

    $pageTitle = trad("Événements");

    include("includes/head.php");
    include("includes/header.php");

    if(isset($_POST['saveUnsaveEvent'])){

        $responseSaveUnsave = file_get_contents("http://localhost:8081/saveUnsaveEvent?id=".$_SESSION['id']."&id_event=".$_POST['saveUnsaveEvent']);

        if($responseSaveUnsave == "save_success"){

            $successMessage = "Événement enregistré, vous pouvez dès à présent le retrouver sur votre profil";

        }elseif($responseSaveUnsave == "unsave_success"){

            $successMessage = "Événement supprimé de vos enregistrements.";

        }

    }

    if(isset($_SESSION['id'])){
            $dataJson = file_get_contents("http://localhost:8081/showDefaultEventsPage?id=". $_SESSION['id']);
            

            $distinctTypes = [];
            $eventList = [];

            if($dataJson){

                $response = json_decode($dataJson, true);

                if(isset($response['error']) && $response['error'] != ""){

                    $errorMessage = $response['error'];

                }else{

                    $distinctTypes = $response['types'];
                    $eventList = $response['events'];

                }

            }
        }else{
            $dataJson = file_get_contents("http://localhost:8081/showDefaultEventsPage");
            

            $distinctTypes = [];
            $eventList = [];

            if($dataJson){

                $response = json_decode($dataJson, true);

                if(isset($response['error']) && $response['error'] != ""){

                    $errorMessage = $response['error'];

                }else{

                    $distinctTypes = $response['types'];
                    $eventList = $response['events'];

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

            $dataJson = file_get_contents("http://localhost:8081/showPersonalizedEventsPage?id=".$id."&research=".$research."&filter=".$filter."&sort=".$sort);

            if($dataJson){

                $response = json_decode($dataJson, true);

                if(isset($response['error']) && $response['error'] != ""){

                    $errorMessage = $response['error'];

                }else{

                    $eventList = $response['events'];

                }

            }

        }

        $notif = [

            "inscription_success" => "Inscription à l'événement validée.",

        ];

        $notifKey = $_GET["notif"] ?? null;

        $successMessage = $notif[$notifKey] ?? null;

        $error = [

            "inscription_error" => "Inscription à l'événement échouée, veuillez réessayer.",

        ];

        $errorKey = $_GET["error"] ?? null;

        $errorMessage = $error[$errorKey] ?? null;

    ?>
    <body>
        <main>
            <div class="backgroundPlain" style="background-color:#f2f6fa; align-items:flex-start;">
                <div class="col-3 ps-4 pe-4" style="color:white; background-color:rgb(62, 134, 189); min-height: 120vh; padding-top:150px; clip-path: polygon(0% 0%, 100% 0%, 100% 90%, 0% 100%);<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                    <h3><?php echo trad("Événements") ?></h3>
                    <div class="line"></div>
                    <p><?php echo trad("Cette page vous donne accès aux événements proposés par l'équipe Silver Happy.") ?></p>

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
                        <p><strong><?php echo trad("Rechercher un événement :") ?></strong></p>
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

                        <p><strong><?php echo trad("Filtrer les événements :") ?></strong></p>
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

                        <p><strong><?php echo trad("Trier les événements :") ?></strong></p>
                        <div class="row">
                            <div class="input-group">
                                <select name="sort" class="selectFilter" onchange="this.form.submit()">
                                    <option value="" disabled selected><?php echo trad("Choisissez une méthode de tri") ?></option>
                                        <option value="1" <?php if(isset($_GET['sort']) && $_GET['sort'] == "1"){ echo 'selected'; } ?>><?php echo trad("Date de début la plus proche") ?></option>
                                        <option value="2" <?php if(isset($_GET['sort']) && $_GET['sort'] == "2"){ echo 'selected'; } ?>><?php echo trad("Date de début la plus tard") ?></option>
                                        <option value="3" <?php if(isset($_GET['sort']) && $_GET['sort'] == "3"){ echo 'selected'; } ?>><?php echo trad("Prix croissant") ?></option>
                                        <option value="4" <?php if(isset($_GET['sort']) && $_GET['sort'] == "4"){ echo 'selected'; } ?>><?php echo trad("Prix décroissant") ?></option>
                                        <option value="5" <?php if(isset($_GET['sort']) && $_GET['sort'] == "5"){ echo 'selected'; } ?>><?php echo trad("Par préférence") ?></option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="lateralAffichage col-9" style="background-color:#f2f6fa;padding-top:140px; padding-bottom:90px;<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>;">
                    <?php if(!empty($eventList)){ ?>
                        <?php foreach($eventList as $event){ ?>

                            <div class="showEvent" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">

                                <div class="row">
                                    <div class="col-10">
                                        <h5><?php echo htmlspecialchars(tradByAPI($event['name'])) ?></h5>
                                        <div class="line"></div>
                                        <p><?php echo htmlspecialchars(trad($event['type'])) ?></p>
                                    </div>
                                    <div class="col-2">
                                        <form method="POST" action="">
                                            <input type="hidden" name="saveUnsaveEvent" value="<?php echo htmlspecialchars($event['id_event']); ?>"></input>
                                            <?php if(!$event['is_saved']){ ?>
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

                                <p><?php echo htmlspecialchars(tradByAPI($event['description'])) ?></p>

                                <p><small> <?php echo trad("À partir de ") ?> <?php echo date("d/m/Y H:i", strtotime($event['date_start'])) ?> <?php echo trad(" jusqu'à ") ?> <?php echo date("d/m/Y H:i", strtotime($event['date_end'])) ?></small></p>
                                <p><small><?php echo $event['price'] ?> <?php echo "€" ?></small></p>
                                <p><small><?php echo $event['capacity'] - $event['nb_inscription'] ?> <?php echo " places restantes" ?></small></p>

                                <?php if(!$event['is_subscribe'] && strtotime($event['date_start']) > time() && $event['capacity'] > $event['nb_inscription']){ ?>
                                    <form method="POST" action="traitementsPHP/checkoutEvents.php">
                                        <button type="<?php echo isset($_SESSION['id']) ? 'submit' : 'button'; ?>" class="inscriptionButton" <?php if(!isset($_SESSION['id'])) { ?> onclick="window.location.href='connexion.php?notif=need_connexion'" <?php } ?>>
                                            s'inscrire
                                        </button>
                                        <input type="hidden" name="id_event" value="<?= $event['id_event'] ?>">
                                        <input type="hidden" name="name" value="<?= $event['name'] ?>">
                                    </form>
                                <?php } ?>
                            </div>

                        <?php } ?>
                    <?php }else{ ?>

                        <p style="justify-self:center;padding-top:150px;"><?php echo trad("Aucun événement n'a été partagé pour le moment.") ?></p>

                    <?php } ?>
                </div>
            </div>
        </main>
        <?php include("includes/footer.php") ?>
    </body>
</html>