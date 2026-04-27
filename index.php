<?php 
    session_start();
    include('traitementsPHP/deconnexionAuto.php'); 

    include("includes/db.php");

    $tuto = false;

    if(isset($_SESSION['id'])){

        $q = 'SELECT tuto_seen FROM CONSUMER WHERE ID_USER = :id';
        $req = $bdd->prepare($q);
        $req->execute(['id' => $_SESSION['id']]);
        $result = $req->fetch(PDO::FETCH_ASSOC);

        if($result && $result['tuto_seen'] == 0){

            $tuto = true;

        }

    }

    if(!isset($_SESSION['id'])){

        $dataJson = file_get_contents("http://localhost:8081/advertisementIndexOffline");

        if($dataJson){

            $advertisement = json_decode($dataJson, true);

        }

    }else{

        $dataJson = file_get_contents("http://localhost:8081/advertisementIndexOnline");

        if($dataJson){

            $advertisement = json_decode($dataJson, true);

        }

    }

?>
<!DOCTYPE html>
<html lang="en">

    <?php 

        $totalAmount = 0;

        if(isset($_SESSION['id'])){

            $q = 'SELECT SUM(QUOTE.amount) as total_unpaid_quote FROM QUOTE INNER JOIN CONSUMER ON QUOTE.ID_CONSUMER = CONSUMER.ID_CONSUMER WHERE QUOTE.status = 1 AND CONSUMER.ID_USER = :id';
            $req = $bdd->prepare($q);
            $req->execute(['id' => $_SESSION['id']]);
            $result = $req->fetch(PDO::FETCH_ASSOC);

            if($result && $result['total_unpaid_quote']){

                $totalAmount = $result['total_unpaid_quote'];

            }

        }

        if(isset($_GET['language_changement_request']) && $_GET['language_changement_request'] == 1){

            $q = 'SELECT language FROM USER_ WHERE ID_USER = :id';
            $req = $bdd->prepare($q);
            $req->execute(['id' => $_SESSION['id']]);
            $userInfo = $req->fetch(PDO::FETCH_ASSOC);

            $_SESSION['language'] = $userInfo['language'];

        }if(isset($_GET['language_changement_request']) && $_GET['language_changement_request'] == "without_connexion"){

            $_SESSION['language'] = $_POST['language'];

        }

        include("includes/translation.php");

        $pageTitle = trad('Page d\'accueil');
        include("includes/head.php");

        $notif = $_GET["notif"] ?? null;

        if ($notif == "connexion_success" && (!isset($_SESSION['status']) || $_SESSION['status'] != 3)) {

            $successMessage = trad("Connexion réussie !");

        }elseif($notif == "account_suppression"){

            $successMessage = trad("Suppression du compte et annulation de l'abonnement effectuée.");

        }

        if ($notif == "quote_paiement_success") {

            $successMessage = trad("Paiement du devis réussi !");

        }elseif($notif == "quote_paiement_error"){

            $errorMessage = trad("Paiement du devis échoué.");

        }
        
        if($notif == "advertisement_success"){

            $successMessage = trad("Paiement réussi, votre profil profite maintenant de la publicité.");

        }

        $errorMessages = [

            "mail_paiement_error" => trad("Erreur lors de l'envoie du mail de confirmation, votre paiement à en revanche bien été pris en compte. Votre profil profitera donc bien de la publicité."),

        ];

        $errorKey = $_GET["error"] ?? null;

        $errorMessage = $errorMessages[$errorKey] ?? null;
    ?>

    <body style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>">
        <?php include("includes/header.php"); ?>

        <main>

            <img src="medias/images/imagePrincipaleAccueil3.png" alt="Image d'accueil" width="100%">

            <div class="part1" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>">
                <h3><?php echo trad("Un accompagnement personnalisé pour une vie apaisée !") ?></h3>
                <div class="line mt-1 mb-1"></div>

                <div class="col-12 pt-1">
                    <?php if(isset($successMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($successMessage); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if(isset($_SESSION['status']) && ($_SESSION['status'] == 1 || $_SESSION['status'] == 2 || $_SESSION['status'] == 5 || $_SESSION['status'] == 6)) { ?>

                    <?php if($totalAmount > 0){ ?>

                        <div class="alert alert-warning mt-2">
                            <a style="color:black;text-decoration:none;" href="traitementsPHP/checkoutQuote.php"><?php echo trad("Veuillez régler votre/vos devis en attente de paiement en cliquant ICI (total = ") . $totalAmount . "€)" ?></a>
                        </div>

                    <?php } ?>

                    <p><?php echo trad("Silver Happy, à vos côtés pour vivre plus librement, plus sereinement et plus heureux après 60 ans.") ?></p>

                    <a href="services.php">
                        <button type="button" class="btn-part1 mt-2">
                            <?php echo trad("Découvrez nos services") ?>
                        </button>
                    </a>

                <?php }else if(isset($_SESSION['status']) && $_SESSION['status'] == 4) { ?>

                    <p><?php echo trad("Silver Happy, à vos côtés pour permettre à nos seniors de vivre plus librement, plus sereinement et plus heureux après 60 ans.") ?></p>

                    <a href="dashboard.php">
                        <button type="button" class="btn-part1 mt-2">
                            <?php echo trad("Partagez vos services") ?>
                        </button>
                    </a>

                <?php }else if(!isset($_SESSION['status']) || ($_SESSION['status'] != 1 && $_SESSION['status'] != 2 && $_SESSION['status'] != 4 && $_SESSION['status'] != 5 && $_SESSION['status'] != 6)){ ?>

                    <p><?php echo trad("Silver Happy, à vos côtés pour vivre plus librement, plus sereinement et plus heureux après 60 ans.") ?></p>

                    <?php if(isset($_SESSION['status']) && $_SESSION['status'] == 3){ ?>
                        <div class="alert alert-success">
                            <p><?php echo trad("Votre dossier est en cours de vérification, merci pour votre compréhension.") ?></p>
                        </div>
                    <?php }else{?>
                    <a href="connexion.php">
                        <button type="button" class="btn-part1 mt-2">
                            <?php echo trad("Commencez par vous connecter") ?>
                        </button>
                    </a>

                <?php }} ?>
            </div>

            <div class="part2" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>">
                <h2><?php echo trad("Nos valeurs, notre mission, notre métier !") ?></h2>
                <div class="line mt-1 mb-1"></div>
                <p><?php echo trad("Chez Silver Happy, nous accompagnons les seniors pour leur permettre de vivre pleinement et sereinement après 60 ans.<br><br>Notre mission est de proposer des services personnalisés, humains et de qualité, pensés pour simplifier le quotidien, favoriser le bien-être et créer du lien. Nous sélectionnons avec soin des prestataires engagés afin de garantir un accompagnement fiable, bienveillant et adapté à chaque situation.<br><br><strong>Parce que bien vieillir, c’est avant tout se sentir écouté, entouré et en confiance.</strong>") ?></p>
            </div>

            <?php if(!isset($_SESSION['id']) || $_SESSION['status'] == 1 || $_SESSION['status'] == 2 || $_SESSION['status'] == 5 || $_SESSION['status'] == 6){ ?>
                <div class="container">
                    <div class="row mt-5 ms-5 pt-3">
                        <h2><?php echo trad("Mise en avant d'un prestataire") ?></h2>
                        <div class="line ms-3"></div>
                    </div>
                    <a class="linkToVisitProfile" style="text-decoration:none;" href="profileVisit.php?visitedId=<?php echo $advertisement['id_service_provider'] ?>">
                        <?php if(isset($advertisement) && (!isset($advertisement['error']) || $advertisement['error'] == "")){ ?>
                            <div class="showEvent" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <?php if(!empty($advertisement['profile_picture'])){ ?>
                                            <img src="data/profils/<?php echo htmlspecialchars($advertisement['profile_picture']); ?>" alt="Photo de profil" class="profilePagePicture" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                                        <?php }else{ ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                                            </svg>
                                        <?php } ?>
                                    </div>
                                    <div class="col">
                                        <h5 class="mb-0"><?php echo htmlspecialchars($advertisement['name'] . " " . $advertisement['surname']); ?></h5>
                                        <small class="text-muted"><?php echo trad("Publicité proposée par Silver Happy"); ?></small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <h5><?php echo htmlspecialchars(tradByAPI($advertisement['title'])); ?></h5>
                                        <div class="line mb-2"></div>
                                        <p><?php echo htmlspecialchars(tradByAPI($advertisement['description'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </a>
                </div>
            <?php } ?>

            <div class="part3">
                <div class="container">
                    <h2><?php echo trad("Notre équipe") ?></h2>
                    <div class="line"></div>
                    <div class="polygone-container" style="margin-top:-60px;">
                        <div class="polygone-img">
                            <img src="medias/images/founder2.jpg" alt="Première photo de l'équipe">
                        </div>
                        <p class="polygone-text" style="margin-top:2%;"><?php echo trad("Silver Happy repose sur des équipes engagées et des prestataires partenaires rigoureusement sélectionnés, tous animés par la même exigence de qualité, d’écoute et de bienveillance.<br>Ensemble, ils œuvrent chaque jour pour proposer aux seniors un accompagnement fiable, personnalisé et profondément humain.") ?></p>

                        <div class="polygone-img" style="margin-top:100px;">
                            <img src="medias/images/founder3.jpg" alt="Deuxième photo de l'équipe">
                        </div>
                        <p  class="polygone-text" style="margin-top:12%;"><?php echo trad("Le projet est à l'initiative de Jean Camus, Thomas Keegan et Claire Christie, trois fondateurs partageant une vision commune : replacer l’humain au cœur des services dédiés aux seniors.<br>Leur volonté a donné naissance à une structure fondée sur la confiance, le respect et l’engagement durable auprès de chaque bénéficiaire.") ?></p>

                        <div class="polygone-img" style="margin-top:200px;">
                            <img src="medias/images/founder1.jpg" alt="Troisième photo de l'équipe">
                        </div>
                    </div>
                </div>
            </div> 

        </main>

        <?php 

            include("includes/footer.php"); 
            include('includes/magnifyingLink.php');

            include('includes/audioLink.php'); 

        ?>
        <?php if(isset($_SESSION['status']) && $_SESSION['status'] == 4){ ?>

            <audio id="audio" src="audios/indexServiceProvider.m4a"></audio>

        <?php }else{ ?>

            <audio id="audio" src="audios/index.m4a"></audio>

        <?php } ?>

        <script src="jsFunctions/hideShowHeader.js"></script>

        <?php if($tuto){ ?>
        
            <link rel="stylesheet" href="cssStyles/styleTuto.css?v=2">

            <div id="tutoBlackBackground" class="tutoBlackBackground"></div>
            <div id="tutoTextZone" class="tutoTextZone">
                <h5>Bienvenue sur Silver Happy !</h5>
                <div class="line mb-3" style="justify-self:center;"></div>
                <p id="tutoText"></p>
                <button id="tutoButton">Suivant</button>
            </div>

            <script>

                let menuElements = document.querySelectorAll('.navbar-nav > li');
                let tutoTexts = [
                    "Explorez notre univers et réservez l'expérience qui vous ressemble.",
                    "Laissez-vous inspirer par nos événements et rejoignez l'aventure.",
                    "Succombez à vos envies et découvrez les pépites de notre boutique.",
                    "Éveillez votre curiosité à travers nos précieux conseils.",
                    "Retrouvez ici le fil de vos échanges et conversations.",
                    "Personnalisez votre espace, retrouvez vos informations et cultivez votre profil à votre image."
                ];
                
                let step = 0;

                function startTuto() {

                menuElements.forEach(el => el.classList.remove('tutoShowZone'));

                    if (step >= tutoTexts.length) {
                        document.getElementById('tutoBlackBackground').remove();
                        document.getElementById('tutoTextZone').remove();
                        fetch('traitementsPHP/tutoValidation.php');
                        return;
                    }

                    if(menuElements[step]) {
                        menuElements[step].classList.add('tutoShowZone');
                    }
                    document.getElementById('tutoText').innerText = tutoTexts[step];
                    
                    if (step === tutoTexts.length - 1) {
                        document.getElementById('tutoButton').innerText = "Terminer";
                    }
                }

                document.getElementById('tutoButton').addEventListener('click', function() {
                    step++;
                    startTuto();
                });

                startTuto();
            </script>

        <?php } ?>

        <script>

            document.getElementById('audioButton').addEventListener('click', function(e){
                e.preventDefault();
                document.getElementById('audio').play();

            })

        </script>

    </body>
    
</html>