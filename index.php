<!DOCTYPE html>
<html lang="en">

    <?php 

        session_start();

        include("includes/db.php");

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

        <?php include("includes/footer.php"); ?>

        <script src="jsFunctions/hideShowHeader.js"></script>

    </body>
    
</html>