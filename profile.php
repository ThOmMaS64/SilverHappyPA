<?php

    session_start();

    include("includes/translation.php");

    $pageTitle = trad("Mon profil");
    include('includes/db.php');

    include("includes/head.php");

    include("includes/header.php");

    if(isset($_GET['notif']) && $_GET['notif'] == "profile_picture_changement_success"){

        $q = 'SELECT profilePicture FROM USER_ WHERE ID_USER = :id';
        $req = $bdd->prepare($q);
        $req->execute(['id' => $_SESSION['id']]);
        $userInfo = $req->fetch(PDO::FETCH_ASSOC);

        $_SESSION['profilePicture'] = $userInfo['profilePicture'];

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

                                                <img src="imagesData/profils/<?php echo htmlspecialchars($_SESSION['profilePicture']); ?>" alt="Photo de profil" class="profilePagePicture">

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
                                    <h3><?php echo $_SESSION['name'] ." " . $_SESSION['surname']?> <small>, <?php echo trad("alias"); ?> <?php echo $_SESSION['username']; ?><small></h3>
                                    <div class="line mb-1"></div>
                                    <p><?php echo trad("Inscrit depuis le") ?> <?php echo date("d/m/Y", strtotime($_SESSION['date_inscription'])); ?></p>
                                    <p><?php echo trad("Adresse email") ?> : <?php echo $_SESSION['email']; ?></p>
                                </div>

                                <div class="col-1" style="margin-left:95%;">
                                    <a href="personalizeProfile.php" class="personalizeProfileLink">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <?php if($_SESSION["keyWord1"] != ""){ echo trad($_SESSION["keyWord1"]); }elseif($_SESSION["keyWord2"] != ""){ echo trad($_SESSION["keyWord"]); }elseif($_SESSION["keyWord3"] != ""){ echo trad($_SESSION["keyWord3"]); } ?>
                            </div>

                            <div class="row">
                                <?php if($_SESSION['description'] != ""){ echo trad($_SESSION['description']); } ?>
                            </div>

                        </div>
                    </div>
                </div>
                
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
                                                    <p><?php echo htmlspecialchars(trad($advice['description'])) ?></p>
                                                </div>
                                            </div>

                                            <p><small> <?php echo trad("Publié par ");?> <?php echo htmlspecialchars($advice['username']) ?> - <?php echo htmlspecialchars(trad($advice['profession'])) ?> </small></p>

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
                            <h5>Mes factures</h5>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include("includes/footer.php") ?>
</body>