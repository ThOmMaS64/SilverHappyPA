<!DOCTYPE html>
<html lang="en">

    <?php 

        session_start();

        if(isset($_POST['saveUnsaveAdvice'])){

             $responseSaveUnsave = file_get_contents("http://localhost:8081/saveUnsaveAdvice?id=".$_SESSION['id']."&id_advice=".$_POST['saveUnsaveAdvice']);

            if($responseSaveUnsave == "save_success"){

                $successMessage = "Conseil enregistré, vous pouvez dès à présent le retrouver sur votre profil";

            }elseif($responseSaveUnsave == "unsave_success"){

                $successMessage = "Conseil supprimé de vos enregistrements.";

            }

        }

        include("includes/translation.php");

        $pageTitle = trad("Conseils");

        include("includes/head.php");
        include("includes/header.php");

        $dataJson = file_get_contents("http://localhost:8081/showDefaultAdvicesPage?id=".$_SESSION['id']);

        $distinctThemes = [];
        $adviceList = [];

        if($dataJson){

            $response = json_decode($dataJson, true);

            if(isset($response['error']) && $response['error'] != ""){

                $errorMessage = $response['error'];

            }else{

                $distinctThemes = $response['themes'];
                $adviceList = $response['advices'];

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

            $dataJson = file_get_contents("http://localhost:8081/showPersonalizedAdvicesPage?id=".$_SESSION['id']."&research=".$research."&filter=".$filter."&sort=".$sort);

            if($dataJson){

                $response = json_decode($dataJson, true);

                if(isset($response['error']) && $response['error'] != ""){

                    $errorMessage = $response['error'];

                }else{

                    $adviceList = $response['advices'];

                }

            }

        }

    ?>

    <body>
        <main>
            <div class="backgroundPlain" style="background-color:#f2f6fa; align-items:flex-start;">
                <div class="col-3 ps-4 pe-4" style="color:white; background-color:rgb(62, 134, 189); min-height: 120vh; padding-top:150px; clip-path: polygon(0% 0%, 100% 0%, 100% 90%, 0% 100%);<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                    <h3><?php echo trad("Conseils") ?></h3>
                    <div class="line"></div>
                    <p><?php echo trad("Cette page vous donne accès aux conseils partagés par l'équipe Silver Happy et ses nombreux prestataires.") ?></p>

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
                        <p><strong><?php echo trad("Rechercher un conseil :") ?></strong></p>
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

                        <p><strong><?php echo trad("Filtrer les conseils :") ?></strong></p>
                        <div class="row mb-5">
                            <div class="input-group">
                                <select name="filter" class="selectFilter" onchange="this.form.submit()">
                                    <option disabled selected><?php echo trad("Choisissez un thème") ?></option>
                                    <?php foreach($distinctThemes as $theme): ?>
                                        <option value="<?= htmlspecialchars($theme) ?>" <?php if(isset($_GET['filter']) && $_GET['filter'] == $theme){ echo 'selected'; } ?> ><?= htmlspecialchars(trad($theme)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <p><strong><?php echo trad("Trier les conseils :") ?></strong></p>
                        <div class="row">
                            <div class="input-group">
                                <select name="sort" class="selectFilter" onchange="this.form.submit()">
                                    <option value="" disabled selected><?php echo trad("Choisissez une méthode de tri") ?></option>
                                        <option value="1" <?php if(isset($_GET['sort']) && $_GET['sort'] == "1"){ echo 'selected'; } ?>><?php echo trad("Du plus récent au plus ancien") ?></option>
                                        <option value="2" <?php if(isset($_GET['sort']) && $_GET['sort'] == "2"){ echo 'selected'; } ?>><?php echo trad("Du plus ancien au plus récent") ?></option>
                                        <option value="3" <?php if(isset($_GET['sort']) && $_GET['sort'] == "3"){ echo 'selected'; } ?>><?php echo trad("Par préférence") ?></option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="lateralAffichage col-9" style="background-color:#f2f6fa;padding-top:140px; padding-bottom:90px;<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>;">
                    <?php if(!empty($adviceList)){ ?>
                        <?php foreach($adviceList as $advice){ ?>

                            <div class="showAdvice" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">

                                <div class="row">
                                    <div class="col-10">
                                        <h5><?php echo htmlspecialchars(trad($advice['title'])) ?></h5>
                                        <div class="line"></div>
                                        <p><?php echo htmlspecialchars(trad($advice['theme'])) ?></p>
                                    </div>
                                    <div class="col-2">
                                        <form method="POST" action="">
                                            <input type="hidden" name="saveUnsaveAdvice" value="<?php echo htmlspecialchars($advice['id_advice']); ?>"></input>
                                            <?php if(!$advice['is_saved']){ ?>
                                                <button class="saveUnsaveButton">
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

                                <p><?php echo htmlspecialchars(trad($advice['description'])) ?></p>

                                <p><small> <?php echo trad("Publié le") ?> <?php echo date("d/m/Y", strtotime($advice['date_publication'])) ?> <?php echo trad("par") ?> <?php echo htmlspecialchars($advice['name']) ?> <?php echo htmlspecialchars($advice['surname']) ?> <?php echo trad("alias") ?> <?php echo htmlspecialchars($advice['username']) ?> - <?php echo htmlspecialchars(trad($advice['profession'])) ?> </small></p>

                            </div>

                        <?php } ?>
                    <?php }else{ ?>

                        <p style="justify-self:center;padding-top:150px;"><?php echo trad("Aucun conseil n'a été partagé pour le moment.") ?></p>

                    <?php } ?>
                </div>
            </div>
        </main>
        <?php include("includes/footer.php") ?>
    </body>
</html>