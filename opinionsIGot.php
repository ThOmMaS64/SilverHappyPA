<?php 
    session_start();
    include('traitementsPHP/deconnexionAuto.php'); ?>
<!DOCTYPE html>
<html lang="en">

    <?php 
        include("includes/translation.php");

        $pageTitle = trad("Avis reçus");

        include("includes/head.php");
        include("includes/header.php");

        $dataJson = file_get_contents("http://localhost:8081/showDefaultGradesIGot?id=" . $_SESSION['id']);

        $gradeList = [];

        if ($dataJson) {
            $response = json_decode($dataJson, true);
            if (!empty($response['error'])) {
                $errorMessage = $response['error'];
            } else {
                $gradeList = $response['grades'];
            }
        }

        if (isset($_GET['sort'])) {

            $dataJson = file_get_contents("http://localhost:8081/showPersonalizedGradesIGot?id=" . $_SESSION['id'] . "&sort=" . urlencode($_GET['sort']));

            if ($dataJson) {
                $response = json_decode($dataJson, true);
                if (!empty($response['error'])) {
                    $errorMessage = $response['error'];
                } else {
                    $gradeList = $response['grades'];
                }
            }
        }
    ?>

    <body>
        <main>
            <div class="backgroundPlain" style="background-color:#f2f6fa; align-items:flex-start;">

                <div class="col-3 ps-4 pe-4" style="color:white; background-color:rgb(62, 134, 189); min-height:120vh; padding-top:150px; clip-path: polygon(0% 0%, 100% 0%, 100% 90%, 0% 100%);<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                    <h3><?php echo trad("Avis reçus") ?></h3>
                    <div class="line"></div>
                    <p><?php echo trad("Cette page vous donne accès aux avis partagés par les adhérents à qui vous avez effectué une prestation de service.") ?></p>

                    <div class="col-12">
                        <?php if (isset($errorMessage)): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($errorMessage); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <form method="GET" action="">
                        <p><strong><?php echo trad("Trier les avis :") ?></strong></p>
                        <div class="input-group">
                            <select name="sort" class="selectFilter" onchange="this.form.submit()">
                                <option value="" disabled selected><?php echo trad("Choisissez une méthode de tri") ?></option>
                                <option value="1" <?php if(isset($_GET['sort']) && $_GET['sort'] == "1") echo 'selected'; ?>><?php echo trad("Du plus récent au plus ancien") ?></option>
                                <option value="2" <?php if(isset($_GET['sort']) && $_GET['sort'] == "2") echo 'selected'; ?>><?php echo trad("Du plus ancien au plus récent") ?></option>
                                <option value="3" <?php if(isset($_GET['sort']) && $_GET['sort'] == "3") echo 'selected'; ?>><?php echo trad("Du meilleur au moins bon") ?></option>
                                <option value="4" <?php if(isset($_GET['sort']) && $_GET['sort'] == "4") echo 'selected'; ?>><?php echo trad("Du moins bon au meilleur") ?></option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="lateralAffichage col-9" style="background-color:#f2f6fa; padding-top:140px; padding-bottom:90px;<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>;">

                    <?php if (!empty($gradeList)): ?>
                        <?php foreach ($gradeList as $grade): ?>

                            <a class="linkToVisitProfile" style="text-decoration:none;" href="profileVisit.php?type=consumer&visitedId=<?php echo $grade['id_consumer'] ?>">
                                <div class="showAdvice" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5><?php echo htmlspecialchars($grade['consumer_name']) ?> <?php echo htmlspecialchars($grade['consumer_surname']) ?> - <?php echo htmlspecialchars(trad($grade['service_type'])) ?></h5>
                                            <div class="line"></div>
                                            <p><?php echo trad("Note") ?> : <?php echo htmlspecialchars($grade['grade']) ?> / 5</p>
                                            <?php if (!empty($grade['description'])): ?>
                                                <p><?php echo htmlspecialchars($grade['description']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </a>

                        <?php endforeach; ?>

                    <?php else: ?>
                        <p style="justify-self:center; padding-top:150px;"><?php echo trad("Aucun avis n'a été partagé pour le moment.") ?></p>
                    <?php endif; ?>

                </div>
            </div>
        </main>
        <?php include("includes/footer.php"); ?>
        <?php include('includes/magnifyingLink.php'); ?>
    </body>
</html>