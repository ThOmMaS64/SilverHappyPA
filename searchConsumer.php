<?php 
    session_start();
    include('traitementsPHP/deconnexionAuto.php'); ?>
<!DOCTYPE html>
<html lang="en">

    <?php 
        include("includes/translation.php");

        $pageTitle = trad("Recherche d'adhérents");

        include("includes/head.php");
        include("includes/header.php");

        if(isset($_GET['research'])){

            $id = isset($_SESSION['id']) ? $_SESSION['id'] : "";

            $dataJson = file_get_contents("http://localhost:8081/showSearchedConsumers?id=".$id."&research=". urlencode($_GET['research']));

            if($dataJson){

                $response = json_decode($dataJson, true);

                if(isset($response['error']) && $response['error'] != ""){

                    $errorMessage = $response['error'];

                }else{

                    $consumerList = $response['consumer'];

                }

            }

        }

    ?>

    <body>
        <main>
            <div class="backgroundPlain" style="background-color:#f2f6fa; align-items:flex-start;">
                <div class="col-3 ps-4 pe-4" style="color:white; background-color:rgb(62, 134, 189); min-height: 120vh; padding-top:150px; clip-path: polygon(0% 0%, 100% 0%, 100% 90%, 0% 100%);<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                    <h3><?php echo trad("Recherche d'adhérents") ?></h3>
                    <div class="line"></div>
                    <p><?php echo trad("Cette page vous donne la possibilité de trouver vos prochains clients en les recherchant avec des mots clés qui vous correspondent.") ?></p>

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
                        <p><strong><?php echo trad("Rechercher un adhérent :") ?></strong></p>
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
                    </form>
                </div>
                <div class="lateralAffichage col-9" style="background-color:#f2f6fa;padding-top:140px; padding-bottom:90px;<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>;">
                    <?php if(!empty($consumerList)){ ?>
                        <?php foreach($consumerList as $consumer){ ?>

                            <a class="linkToVisitProfile" style="text-decoration:none;" href="profileVisit.php?type=consumer&visitedId=<?php echo $consumer['id_consumer'] ?>">
                                <div class="showAdvice" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">

                                    <div class="row">
                                        <div class="col-10">
                                            <h5><?php echo htmlspecialchars(tradByAPI($consumer['name'])) ?><?= " " ?><?php echo htmlspecialchars(tradByAPI($consumer['surname'])) ?><?= " - " ?><?php echo htmlspecialchars(tradByAPI($consumer['username'])) ?></h5>
                                            <div class="line"></div>
                                            <p><?php echo htmlspecialchars(trad($consumer['description'])) ?></p>
                                            <?php if(!empty($consumer['key_word1']) || !empty($consumer['key_word2']) || !empty($consumer['key_word3'])){ ?>
                                                <p><?php echo htmlspecialchars(trad($consumer['key_word1'])) ?><?= " - " ?><?php echo htmlspecialchars(trad($consumer['key_word2'])) ?><?= " - " ?><?php echo htmlspecialchars(trad($consumer['key_word3'])) ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                            </a>

                        <?php } ?>
                    <?php }else{ ?>

                        <p style="justify-self:center;padding-top:150px;"><?php echo trad("Aucun adhérent n'a été trouvé pour le moment.") ?></p>

                    <?php } ?>
                </div>
            </div>
        </main>
        <?php include("includes/footer.php");
        include('includes/magnifyingLink.php'); 
        include('includes/audioLink.php'); ?>

        <audio id="audio" src="audios/searchConsumer.m4a"></audio>

        <script>

            document.getElementById('audioButton').addEventListener('click', function(e){
                e.preventDefault();
                document.getElementById('audio').play();

            })

        </script>
    </body>
</html>