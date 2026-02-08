<!DOCTYPE html>
<html lang="en">

    <?php 
        $pageTitle = 'Page d\'accueil';
        include("includes/head.php");
    ?>

    <body>
        <?php include("includes/header.php"); ?>

        <main>

            <img src="medias/images/imagePrincipaleAccueil3.png" alt="Image d'accueil" width="100%">

            <div class="part1">
                <h3>Un accompagnement personnalisé pour une vie apaisée !</h3>
                <div class="line mt-1 mb-1"></div>

                <?php if((isset($_SESSION['connected']) && $_SESSION['connected'] == 1)) { ?>

                    <p>Silver Happy, à vos côtés pour vivre plus librement, plus sereinement et plus heureux après 60 ans.</p>

                    <a href="services.php">
                        <button type="button" class="btn-part1 mt-2">
                            Découvrez nos services
                        </button>
                    </a>

                <?php }else if(isset($_SESSION['connected']) && $_SESSION['connected'] == 2) { ?>

                    <p>Silver Happy, à vos côtés pour permettre à nos seniors de vivre plus librement, plus sereinement et plus heureux après 60 ans.</p>

                    <a href="dashboard.php">
                        <button type="button" class="btn-part1 mt-2">
                            Partagez vos services
                        </button>
                    </a>

                <?php }else if(!isset($_SESSION['connected'])){ ?>

                    <p>Silver Happy, à vos côtés pour vivre plus librement, plus sereinement et plus heureux après 60 ans.</p>

                    <a href="connexion.php">
                        <button type="button" class="btn-part1 mt-2">
                            Commencez par vous connectez
                        </button>
                    </a>

                <?php } ?>
            </div>

            <div class="part2">
                <h2>Nos valeurs, notre mission, notre métier !</h2>
                <div class="line mt-1 mb-1"></div>
                <p>Chez Silver Happy, nous accompagnons les seniors pour leur permettre de vivre pleinement et sereinement après 60 ans.<br><br>Notre mission est de proposer des services personnalisés, humains et de qualité, pensés pour simplifier le quotidien, favoriser le bien-être et créer du lien. Nous sélectionnons avec soin des prestataires engagés afin de garantir un accompagnement fiable, bienveillant et adapté à chaque situation.<br><br><strong>Parce que bien vieillir, c’est avant tout se sentir écouté, entouré et en confiance.</strong></p>
            </div>

            <div class="part3">
                <div class="container">
                    <h2>Notre équipe</h2>
                    <div class="line"></div>
                    <div class="polygone-container" style="margin-top:-60px;">
                        <div class="polygone-img">
                            <img src="medias/images/founder2.jpg" alt="Première photo de l'équipe">
                        </div>
                        <p class="polygone-text" style="margin-top:2%;">Silver Happy repose sur des équipes engagées et des prestataires partenaires rigoureusement sélectionnés, tous animés par la même exigence de qualité, d’écoute et de bienveillance.<br>Ensemble, ils œuvrent chaque jour pour proposer aux seniors un accompagnement fiable, personnalisé et profondément humain.</p>

                        <div class="polygone-img" style="margin-top:100px;">
                            <img src="medias/images/founder3.jpg" alt="Deuxième photo de l'équipe">
                        </div>
                        <p  class="polygone-text" style="margin-top:12%;">Le projet est à l'initiative de Jean Camus, Thomas Keegan et Claire Christie, trois fondateurs partageant une vision commune : replacer l’humain au cœur des services dédiés aux seniors.<br>Leur volonté a donné naissance à une structure fondée sur la confiance, le respect et l’engagement durable auprès de chaque bénéficiaire.</p>

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