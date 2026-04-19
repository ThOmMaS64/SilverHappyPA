<?php 
    session_start();
    include('traitementsPHP/deconnexionAuto.php'); ?>
<?php

    include("includes/translation.php");

    $pageTitle = trad("Mon profil");
    include('includes/db.php');

    include("includes/head.php");

    include("includes/header.php");

    if(isset($_GET['notif']) && $_GET['notif'] == "profile_picture_changement_success"){

        $dataJson = file_get_contents("http://localhost:8081/showUpdatedData?id=".$_SESSION['id']."&ask=1");

        if($dataJson){

            $response = json_decode($dataJson, true);

            if(isset($response['profilePicture'])){

                $_SESSION['profilePicture'] = $response['profilePicture'];

            }
        }

    }

    if(isset($_GET['notif']) && $_GET['notif'] == "description_keyword_update_success"){

        $dataJson = file_get_contents("http://localhost:8081/showUpdatedData?id=".$_SESSION['id']."&ask=2");

        if($dataJson){

            $response = json_decode($dataJson, true);

            if(isset($response['description'])){

                $_SESSION['description'] = $response['description'];
                $_SESSION['keyWord1'] = $response['keyWord1'];
                $_SESSION['keyWord2'] = $response['keyWord2'];
                $_SESSION['keyWord3'] = $response['keyWord3'];

            }
        }

        $_SESSION['personalizeInputs'] = 0;

    }

    if(isset($_GET['notif']) && $_GET['notif'] == "cancel_success"){

        $_SESSION['personalizeInputs'] = 0;

    }

    $dataJson = file_get_contents("http://localhost:8081/showSavedAdvices?id=".$_SESSION['id']);

    if($dataJson){

        $response = json_decode($dataJson, true);

        if(isset($response['error']) && $response['error'] != ""){

            $errorMessage = $response['error'];

        }else{

            $adviceList = $response['advices'];

        }

    }

    if(isset($_POST['id_advice'])){

        $response = file_get_contents("http://localhost:8081/saveUnsaveAdvice?id=".$_SESSION['id']."&id_advice=".$_POST['id_advice']);

        header("location:profile.php");
        exit();

    }

    $dataJson = file_get_contents("http://localhost:8081/showSavedEvent?id=".$_SESSION['id']);

    if($dataJson){

        $response = json_decode($dataJson, true);

        if(isset($response['error']) && $response['error'] != ""){

            $errorMessage = $response['error'];

        }else{

            $eventList = $response['events'];

        }

    }

    if(isset($_POST['id_event'])){

        $response = file_get_contents("http://localhost:8081/saveUnsaveEvent?id=".$_SESSION['id']."&id_event=".$_POST['id_event']);

        header("location:profile.php");
        exit();

    }

    $dataJson = file_get_contents("http://localhost:8081/showSavedService?id=".$_SESSION['id']);

    if($dataJson){

        $response = json_decode($dataJson, true);

        if(isset($response['error']) && $response['error'] != ""){

            $errorMessage = $response['error'];

        }else{

            $serviceList = $response['services'];

        }

    }

    if(isset($_POST['ID_SERVICE'])){

        $response = file_get_contents("http://localhost:8081/saveUnsaveService?id=".$_SESSION['id']."&ID_SERVICE=".$_POST['ID_SERVICE']);

        header("location:profile.php");
        exit();

    }

    $dataJson = file_get_contents("http://localhost:8081/showRegisteredEvent?id=".$_SESSION['id']);

    if($dataJson){

        $response = json_decode($dataJson, true);

        if(isset($response['error']) && $response['error'] != ""){

            $errorMessage = $response['error'];

        }else{

            $eventRegisteredList = $response['events'];

        }

    }

    $dataJson = file_get_contents("http://localhost:8081/showRegisteredService?id=".$_SESSION['id']);

    if($dataJson){

        $response = json_decode($dataJson, true);

        if(isset($response['error']) && $response['error'] != ""){

            $errorMessage = $response['error'];

        }else{

            $serviceRegisteredList = $response['services'];

        }

    }

    $dataJson = file_get_contents("http://localhost:8081/showInvoices?id=".$_SESSION['id']);

    if($dataJson){

        $response = json_decode($dataJson, true);

        if(isset($response['error']) && $response['error'] != ""){

            $errorMessage = $response['error'];

        }else{

            $invoicesList = $response['invoices'];

        }

    }

    $dataJson = file_get_contents("http://localhost:8081/showQuotes?id=".$_SESSION['id']);

    if($dataJson){

        $response = json_decode($dataJson, true);

        if(isset($response['error']) && $response['error'] != ""){

            $errorMessage = $response['error'];

        }else{

            $quotesList = $response['quotes'];

        }

    }

    if(!isset($_SESSION['personalizeInputs'])){
        $_SESSION['personalizeInputs'] = 0;
    }

    if(isset($_POST['personalizeProfileLink'])){

        if($_SESSION['personalizeInputs'] == 0){
            $_SESSION['personalizeInputs'] = 1;
        }else{
            $_SESSION['personalizeInputs'] = 0;
        }
    }

?>

<body style="background-color:rgb(62, 134, 189);<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;<?php endif;endif; ?>;">
    <main>
        <div class="backgroundPlain" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;<?php endif;endif; ?>; align-items:flex-start; padding-top:150px;overflow-y:hidden;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <div class="backgroundForm" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;color:white;<?php endif;endif; ?>">

                            <div class="row">
                                <div class="col-2">
                                    <form method="POST" action="http://localhost:8081/updateProfilPicture?id=<?php echo $_SESSION['id'] ?>" enctype="multipart/form-data">

                                        <label for="myProfilePicture" class="profilePagePictureZone">

                                            <?php if(!empty($_SESSION['profilePicture'])){ ?>

                                                <img src="data/profils/<?php echo htmlspecialchars($_SESSION['profilePicture']); ?>" alt="Photo de profil" class="profilePagePicture">

                                            <?php }else{ ?>

                                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                                                </svg>

                                            <?php } ?>

                                        </label>

                                        <input style="display:none;" type="file" id="myProfilePicture" name="myProfilePicture" accept="image/png, image/jpeg, image/webp, image/jpg" onchange="this.form.submit();">

                                    </form>
                                </div>
                                <div class="col-9">
                                    <h3><?php echo $_SESSION['name'] ." " . $_SESSION['surname']?> <small>, <?php echo trad("alias"); ?> <?php echo $_SESSION['username']; ?></small></h3>
                                    <div class="line mb-1"></div>
                                    <?php if(isset($_SESSION['profession'])){ ?>
                                        <p><?php echo trad("Profession/spécialisation : ") ?> <?php echo $_SESSION['profession']; ?></p>
                                    <?php } ?>
                                    <?php if(isset($_SESSION['birth_date'])){ ?>
                                        <p><?php echo trad("Date de naissance :") ?> <?php echo date("d/m/Y", strtotime($_SESSION['birth_date'])) ?></p>
                                    <?php } ?>
                                    <p><?php echo trad("Inscrit depuis le :") ?> <?php echo date("d/m/Y", strtotime($_SESSION['date_inscription'])); ?></p>
                                    <p><?php echo trad("Adresse email") ?> : <?php echo $_SESSION['email']; ?></p>
                                </div>

                                <div class="col-1" style="margin-left:95%;">
                                    <?php if($_SESSION['personalizeInputs'] == 0){ ?>
                                        <form method="POST" action="" class="personalizeProfileLink">
                                            <input type="hidden" name="personalizeProfileLink" value="<?php echo $_SESSION['id']; ?>">
                                            <button type="submit" style="border:none;background:none;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                            <form method="POST" action="http://localhost:8081/personalizeKeyWordDescription">
                                <div class="row mb-3">
                                    <?php if($_SESSION['personalizeInputs'] == 1){ ?>

                                        <input type="hidden" name="id" value="<?php echo $_SESSION['id']; ?>">

                                        <label><?php echo trad("Personalisez vos mots clés (des mots représentant ce qui vous importe et ce que vous êtes venus chercher sur Silver Happy, exemple : sport, alimentation, sommeil...)") ?> :</label>
                                        <div class="row">
                                            <div class="col-4">
                                                <input class="form-control" type="text" name="keyWord1" value="<?php echo tradByAPI($_SESSION['keyWord1']) ?>">
                                            </div>
                                            <div class="col-4">
                                                <input class="form-control" type="text" name="keyWord2" value="<?php echo tradByAPI($_SESSION['keyWord2']) ?>">
                                            </div>
                                            <div class="col-4">
                                                <input class="form-control" type="text" name="keyWord3" value="<?php echo tradByAPI($_SESSION['keyWord3']) ?>">
                                            </div>
                                        </div>
                                    <?php }elseif($_SESSION['personalizeInputs'] == 0){ ?>
                                    <?php if($_SESSION["keyWord1"] != ""){ ?> <p> Mots clés : <strong> <?php echo $_SESSION["keyWord1"]; } if($_SESSION["keyWord2"] != ""){ ?> </strong> - <strong> <?php echo $_SESSION["keyWord2"]; } if($_SESSION["keyWord3"] != ""){ ?> </strong> - <strong> <?php echo $_SESSION["keyWord3"]; } ?> </strong></p>
                                    <?php } ?>
                                </div>

                                <div class="row mb-4">
                                    <?php if($_SESSION['personalizeInputs'] == 1){ ?>
                                        <label><?php echo trad("Personalisez votre description") ?> :</label>
                                        <textarea class="form-control" rows="2" name="description"><?php echo tradByAPI($_SESSION['description']) ?></textarea>
                                    <?php }elseif($_SESSION['description'] != ""){ ?> <p> <?php echo tradByAPI($_SESSION['description']); } ?></p>
                                </div>

                                <?php if($_SESSION['personalizeInputs'] == 1){ ?>
                                    <div class="row flex-column align-items-center">
                                        <button type="submit" class="btn buttonValidProfileModification"><?php echo trad("Valider les modifications") ?></button>
                                        <button type="submit" name="cancel" value="1" class="btn buttonCancelProfileModification mt-2"><?php echo trad("Annuler") ?></button>
                                    </div>
                                <?php } ?>

                            </form>

                        </div>
                    </div>
                </div>
                
                <?php if($_SESSION['status'] == 1 || $_SESSION['status'] == 2 || $_SESSION['status'] == 5 || $_SESSION['status'] == 6){ ?>
                <div class="row mt-4 justify-content-center">
                    <div class="col-5">
                        <div class="backgroundForm" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;color:white;<?php endif;endif; ?>; margin-bottom:80px;">
                            <h5><?php echo trad("Mes conseils enregistrés") ?></h5>
                            <div class="line mb-2"></div>

                            <div class="scrollZone">
                                <?php if(!empty($adviceList)){ ?>
                                    <?php $isFirstAdvice = true; ?>
                                    <?php foreach($adviceList as $advice){ ?>

                                            <?php if($isFirstAdvice == false){ ?>
                                                <div class="line2 mt-2 mb-2"></div>
                                            <?php } ?>

                                            <div class="row">
                                                <div class="col-2 mt-1 unsaveAdviceProfilePage">
                                                    <form method="POST" action="">
                                                        <input type="hidden" name="id_advice" value="<?php echo $advice['id_advice']; ?>">

                                                        <button type="submit" style="background:none;border:none;padding:0px;display:flex;align-items:center;color:inherit;line-height:1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="col-10">
                                                    <p><?php echo htmlspecialchars(tradByAPI($advice['description'])) ?></p>
                                                </div>
                                            </div>

                                            <p><small> <?php echo trad("Publié par ");?> <?php echo htmlspecialchars($advice['username']) ?> - <?php echo htmlspecialchars(tradByAPI($advice['profession'])) ?> </small></p>

                                            <?php $isFirstAdvice = false; ?>

                                    <?php } ?>
                                <?php }else{ ?>

                                    <p style="justify-self:center;padding-top:10px;"><?php echo trad("Vous n'avez enregistré aucun conseil pour le moment.") ?></p>

                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-5">
                        <div class="backgroundForm" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;color:white;<?php endif;endif; ?>">
                            <h5><?php echo trad("Mes factures") ?></h5>
                            <div class="line mb-2"></div>

                            <div class="scrollZone">
                                <?php if(!empty($invoicesList)){ ?>
                                    <?php $isFirstInvoice = true; ?>
                                    <?php foreach($invoicesList as $invoice){ ?>

                                        <?php if($isFirstInvoice == false){ ?>
                                            <div class="line2 mt-2 mb-2"></div>
                                        <?php } ?>

                                        <?php
                                            if($invoice['invoice_type'] === 'event'){

                                                $typeLabel = trad("Événement");
                                                $typeFolder = "invoices_events";

                                            }elseif($invoice['invoice_type'] === 'service'){

                                                $typeLabel = trad("Prestation de service");
                                                $typeFolder = "invoices_services";

                                            }elseif($invoice['invoice_type'] === 'serviceByQuote'){
                                            
                                                $typeLabel = trad("Prestation de service");
                                                $typeFolder = "invoices_quotes";
                                            
                                            }else{

                                                $typeLabel = trad("Commande");
                                                $typeFolder = "invoices_store";
                                            }
                                        ?>

                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <p><strong><?php echo $typeLabel ?></strong></p>
                                                <p><small><?php echo trad("Émise le") ?> <?php echo date("d/m/Y", strtotime($invoice['date_emission'])) ?></small></p>
                                            </div>
                                            <div class="col-4 text-end">
                                                <a href="data/<?php echo $typeFolder ?>/<?php echo htmlspecialchars($invoice['pdf_path']) ?>" target="_blank" class="btn btn-sm">
                                                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
                                                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                                                    </svg>
                                                    <?php echo trad("Ouvrir") ?>
                                                </a>
                                            </div>
                                        </div>

                                        <?php $isFirstInvoice = false; ?>

                                    <?php } ?>
                                <?php }else{ ?>
                                    <p style="justify-self:center;padding-top:10px;"><?php echo trad("Vous n'avez aucune facture pour le moment.") ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center"  style="margin-top:-50px;">
                    <div class="col-5">
                        <div class="backgroundForm" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;color:white;<?php endif;endif; ?>; margin-bottom:80px;">
                            <h5><?php echo trad("Mes événements enregistrés") ?></h5>
                            <div class="line mb-2"></div>

                            <div class="scrollZone">
                                <?php if(!empty($eventList)){ ?>
                                    <?php $isFirstEvent = true; ?>
                                    <?php foreach($eventList as $event){ ?>

                                            <?php if($isFirstEvent == false){ ?>
                                                <div class="line2 mt-2 mb-2"></div>
                                            <?php } ?>

                                            <div class="row">
                                                <div class="col-2 mt-1 unsaveEventProfilePage">
                                                    <form method="POST" action="">
                                                        <input type="hidden" name="id_event" value="<?php echo $event['id_event']; ?>">

                                                        <button type="submit" style="background:none;border:none;padding:0px;display:flex;align-items:center;color:inherit;line-height:1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="col-10">
                                                    <p><?php echo htmlspecialchars(tradByAPI($event['name'])) ?></p>
                                                </div>
                                            </div>
                                            <p><?php echo htmlspecialchars(tradByAPI($event['description'])) ?></p>
                                            <p><small>Du <?php echo htmlspecialchars($event['date_start']) ?> jusqu'au <?php echo htmlspecialchars($event['date_end']) ?> </small></p>

                                            <?php $isFirstEvent = false; ?>

                                    <?php } ?>
                                <?php }else{ ?>

                                    <p style="justify-self:center;padding-top:10px;"><?php echo trad("Vous n'avez enregistré aucun événement pour le moment.") ?></p>

                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-5">
                        <div class="backgroundForm" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;color:white;<?php endif;endif; ?>">
                            <h5><?php echo trad("Les événements auxquels je suis inscrit") ?></h5>
                            <div class="line mb-2"></div>

                            <div class="scrollZone" style="max-height:200px;">
                                <?php if(!empty($eventRegisteredList)){ ?>
                                    <?php $isFirstEvent = true; ?>
                                    <?php foreach($eventRegisteredList as $event){ ?>

                                            <?php if($isFirstEvent == false){ ?>
                                                <div class="line2 mt-2 mb-2"></div>
                                            <?php } ?>

                                            <div class="row">
                                                <div class="col-10">
                                                    <p><?php echo htmlspecialchars(tradByAPI($event['name'])) ?></p>
                                                </div>
                                            </div>
                                            <p><?php echo htmlspecialchars(tradByAPI($event['description'])) ?></p>
                                            <p><small>Du <?php echo htmlspecialchars($event['date_start']) ?> jusqu'au <?php echo htmlspecialchars($event['date_end']) ?> </small></p>

                                            <?php $isFirstEvent = false; ?>

                                    <?php } ?>
                                <?php }else{ ?>

                                    <p style="justify-self:center;padding-top:10px;"><?php echo trad("Vous n'avez enregistré aucun événement pour le moment.") ?></p>

                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row justify-content-center"  style="margin-top:-50px;">
                    <div class="col-5">
                        <div class="backgroundForm" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;color:white;<?php endif;endif; ?>; margin-bottom:80px;">
                            <h5><?php echo trad("Mes prestations enregistrées") ?></h5>
                            <div class="line mb-2"></div>

                            <div class="scrollZone">
                                <?php if(!empty($serviceList)){ ?>
                                    <?php $isFirstService = true; ?>
                                    <?php foreach($serviceList as $service){ ?>

                                            <?php if($isFirstService == false){ ?>
                                                <div class="line2 mt-2 mb-2"></div>
                                            <?php } ?>

                                            <div class="row">
                                                <div class="col-2 mt-1 unsaveServicetProfilePage">
                                                    <form method="POST" action="">
                                                        <input type="hidden" name="ID_SERVICE" value="<?php echo $service['ID_SERVICE']; ?>">

                                                        <button type="submit" style="background:none;border:none;padding:0px;display:flex;align-items:center;color:inherit;line-height:1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="col-10">
                                                    <p><?php echo htmlspecialchars(trad($service['type'])) ?></p>
                                                </div>
                                            </div>
                                            <p><?php echo htmlspecialchars(tradByAPI($service['description'])) ?></p>

                                            <?php $isFirstService = false; ?>

                                    <?php } ?>
                                <?php }else{ ?>

                                    <p style="justify-self:center;padding-top:10px;"><?php echo trad("Vous n'avez enregistré aucune prestation pour le moment.") ?></p>

                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-5">
                        <div class="backgroundForm" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;color:white;<?php endif;endif; ?>; margin-bottom:80px;">
                            <h5><?php echo trad("Prestations auxquelles je me suis inscrit") ?></h5>
                            <div class="line mb-2"></div>

                            <div class="scrollZone">
                                <?php if(!empty($serviceRegisteredList)){ ?>
                                    <?php $isFirstService = true; ?>
                                    <?php foreach($serviceRegisteredList as $service){ ?>

                                            <?php if($isFirstService == false){ ?>
                                                <div class="line2 mt-2 mb-2"></div>
                                            <?php } ?>

                                            <div class="row">
                                                <div class="col-10">
                                                    <p><?php echo htmlspecialchars(trad($service['type'])) ?></p>
                                                </div>
                                            </div>
                                            <p><?php echo htmlspecialchars(tradByAPI($service['description'])) ?></p>

                                            <?php $isFirstService = false; ?>

                                    <?php } ?>
                                <?php }else{ ?>

                                    <p style="justify-self:center;padding-top:10px;"><?php echo trad("Vous n'êtes inscrit à aucune prestation pour le moment.") ?></p>

                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center"  style="margin-top:-50px;">
                    <div class="col-5">
                        <div class="backgroundForm" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;color:white;<?php endif;endif; ?>; margin-bottom:80px;">
                            <h5><?php echo trad("Mes devis") ?></h5>
                            <div class="line mb-2"></div>

                            <div class="scrollZone">
                                <?php if(!empty($quotesList)){ ?>
                                    <?php $isFirstQuote = true; ?>
                                    <?php foreach($quotesList as $quote){ ?>

                                        <?php if($isFirstQuote == false){ ?>
                                            <div class="line2 mt-2 mb-2"></div>
                                        <?php } ?>

                                        <div class="row align-items-center">
                                            <div class="col-6">
                                                <p><?php echo htmlspecialchars($quote['prestation']) ?> - <small><?php if(isset($quote['status']) && $quote['status'] == 0){ echo trad("en attente"); }elseif(isset($quote['status']) && $quote['status'] == 1){ echo trad("validé en attente de paiement"); }elseif(isset($quote['status']) && $quote['status'] == 2){ echo trad("validé et réglé"); }elseif(isset($quote['status']) && $quote['status'] == 3){ echo trad("refusé"); } ?></small></p>
                                            </div>
                                            <div class="col-2 text-end">
                                                <a href="data/quotes/<?php echo htmlspecialchars($quote['pdf_path']) ?>" target="_blank" class="btn btn-sm">
                                                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
                                                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                                                    </svg>
                                                    <?php echo trad("Ouvrir") ?>
                                                </a>
                                            </div>
                                            <?php if(isset($quote['status']) && $quote['status'] == 0){ ?>
                                                <div class="col-2">
                                                    <form method="POST" action="http://localhost:8081/acceptQuote?id=<?= $_SESSION['id'] ?>&id_quote=<?= $quote['id_quote'] ?>">
                                                        <button style="border:none;" type="submit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="col-2">
                                                    <form method="POST" action="http://localhost:8081/refuseQuote?id=<?= $_SESSION['id'] ?>&id_quote=<?= $quote['id_quote'] ?>">
                                                        <button style="border:none;" type="submit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <?php $isFirstQuote = false; ?>

                                    <?php } ?>
                                <?php }else{ ?>
                                    <p style="justify-self:center;padding-top:10px;"><?php echo trad("Vous n'avez aucun devis pour le moment.") ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>
    <?php include("includes/footer.php");
    include('includes/magnifyingLink.php'); ?>
</body>