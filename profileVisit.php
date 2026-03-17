<?php

    session_start();

    include("includes/translation.php");

    $pageTitle = trad("Profil");
    include('includes/db.php');

    include("includes/head.php");
    include("includes/header.php");


    $response = file_get_contents("http://localhost:8081/getVisitedPageData?visitedId=".$_GET['visitedId']);

    $visitedPageData = json_decode($response, true);

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
                                    <label for="myProfilePicture" class="profilePagePictureZone">

                                        <?php if(!empty($visitedPageData['profile_picture'])){ ?>

                                            <img src="imagesData/profils/<?php echo htmlspecialchars($visitedPageData['profile_picture']); ?>" alt="Photo de profil" class="profilePagePicture">

                                        <?php }else{ ?>

                                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                                            </svg>

                                        <?php } ?>

                                    </label>
                                </div>
                                <div class="col-9">
                                    <h3><?php echo $visitedPageData['name'] ." " . $visitedPageData['surname']?> <small>, <?php echo trad("alias"); ?> <?php echo $visitedPageData['username']; ?><small></h3>
                                    <div class="line mb-1"></div>
                                    <p><?php echo trad("Profession/spécialisation : ") ?> <?php echo $visitedPageData['profession']; ?></p>
                                    <p><?php echo trad("Dernière connexion : ") ?> <?php echo $visitedPageData['last_connection'] == "2000-01-01 00:00:00" ? trad("Cet utilisateur ne s'est jamais connecté depuis son inscription") : date("d/m/Y", strtotime($visitedPageData['last_connection'])); ?>
                                    <p><?php echo trad("Inscrit depuis le : ") ?> <?php echo date("d/m/Y", strtotime($visitedPageData['date_inscription'])); ?>
                                    <p><?php echo trad("Adresse email") ?> : <?php echo $visitedPageData['email']; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <?php if($visitedPageData["keyWord1"] != ""){ echo tradByAPI($visitedPageData["keyWord1"]); }elseif($visitedPageData["keyWord2"] != ""){ echo tradByAPI($visitedPageData["keyWord2"]); }elseif($visitedPageData["keyWord3"] != ""){ echo tradByAPI($visitedPageData["keyWord3"]); } ?>
                            </div>

                            <div class="row">
                                <?php if($visitedPageData['description'] != ""){ echo tradByAPI($visitedPageData['description']); } ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include("includes/footer.php") ?>
</body>