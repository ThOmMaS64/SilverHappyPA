<?php 
    session_start();
    include('traitementsPHP/deconnexionAuto.php'); ?>
<?php

    include("includes/translation.php");

    $pageTitle = trad("Profil");
    include('includes/db.php');

    include("includes/head.php");
    include("includes/header.php");

    if(isset($_GET['type']) && $_GET['type'] == "consumer"){

        $response = file_get_contents("http://localhost:8081/getVisitedPageData?type=consumer&visitedId=".$_GET['visitedId']);

        $visitedPageData = json_decode($response, true);

    }else{

        $response = file_get_contents("http://localhost:8081/getVisitedPageData?visitedId=".$_GET['visitedId']);

        $visitedPageData = json_decode($response, true);

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
                                    <div class="row">
                                        <div class="col-10">
                                            <h3><?php echo $visitedPageData['name'] ." " . $visitedPageData['surname']?> <small>, <?php echo trad("alias"); ?> <?php echo $visitedPageData['username']; ?></small></h3>
                                        </div>
                                        <div class="col-2">
                                            <a href="http://localhost:8081/openOrCreateDiscussionFromProfile?id=<?php echo $_SESSION['id']; ?>&visited_user_id=<?php echo $visitedPageData['id_user']; ?>" class="btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-chat-text" viewBox="0 0 16 16">
                                                    <path d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/>
                                                    <path d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8m0 2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="line mb-1"></div>
                                    <?php if(!isset($_GET['type'])){ ?>
                                        <p><?php echo trad("Profession/spécialisation : ") ?> <?php echo $visitedPageData['profession']; ?></p>
                                    <?php } ?>
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
    <?php include("includes/footer.php");
    include('includes/magnifyingLink.php'); ?>
</body>