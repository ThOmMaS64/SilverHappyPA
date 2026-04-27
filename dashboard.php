<?php 
    session_start();
    include('traitementsPHP/deconnexionAuto.php'); ?>
<!DOCTYPE html>
<html lang="en">

    <?php 

        include("includes/translation.php");

        $pageTitle = trad("Tableau de bord");

        include("includes/head.php");
        include("includes/header.php");

        if(isset($_SESSION['id'])){

            $dataJson = file_get_contents("http://localhost:8081/showServices");

            $data = json_decode($dataJson, true);
            $distinctServices = $data['services'];
            
        }else{
            
            header("location:connexion.php?need_connexion");
            return;

        }

        if(isset($_GET['chosenService'])){

            $chosenService = urlencode($_GET['chosenService']);

            $dataJson = file_get_contents("http://localhost:8081/showNeededDocuments?service=" . $chosenService);

            $data = json_decode($dataJson, true);
            $neededDocuments = (is_array($data) && isset($data['documents'])) ? $data['documents'] : [];

        }

    ?>

    <body>
        <main>
            <div class="backgroundPlain" style="background-color:#f2f6fa; align-items:flex-start;">
                <div class="col-3 ps-4 pe-4" style="color:white; background-color:rgb(62, 134, 189); min-height: 120vh; padding-top:150px; clip-path: polygon(0% 0%, 100% 0%, 100% 90%, 0% 100%);<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                    <h3><?php echo trad("Tableau de bord") ?></h3>
                    <div class="line"></div>
                    <p><?php echo trad("Cette page vous donne accès à votre tableau de bord, vous pouvez y gérer vos services et proposer des conseils.") ?></p>

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
                </div>
                <div class="lateralAffichage col-9" style="background-color:#f2f6fa;padding-top:140px; padding-bottom:90px;<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>;">
                    
                    <h5 class="mt-3">Proposez un nouveau service</h5>
                    <div class="line mb-4"></div>

                    <form action="http://localhost:8081/showNeededDocuments" method="POST">
                        <div class="col-4">
                            <div class="row mb-5">
                                <div class="input-group">
                                    <label class="mb-1">Choisissez un service parmi cette sélection :</label>
                                    <select name="chosenService" class="selectFilter" onchange="this.form.submit()">
                                            <option disabled selected><?php echo trad("Choisissez un service") ?></option>
                                        <?php foreach($distinctServices as $service): ?>
                                            <option value="<?= htmlspecialchars($service) ?>" <?php if(isset($_GET['chosenService']) && $_GET['chosenService'] == $service){ echo 'selected'; } ?> ><?= htmlspecialchars(trad($service)) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                        
                            <?php if(!empty($neededDocuments)){ ?>
                                <div class="row">

                                    <p>Documents nécessaires :</p>
                                    <?php foreach($neededDocuments as $document): ?>

                                        <div class="mb-3">
                                            <label class="form-label"><?= htmlspecialchars(trad($document)) ?> :</label>
                                            <input type="file" class="form-control" required>
                                            
                                        </div>

                                    <?php endforeach; ?>
                                    
                                </div>
                            <?php } elseif(isset($_GET['chosenService'])) { ?>
                            
                                <p>Aucun document requis pour ce service.</p>

                            <?php } ?>
                        </div>
                    </form>

                </div>
            </div>
        </main>
        <?php include("includes/footer.php");
        include('includes/magnifyingLink.php'); 
        include('includes/audioLink.php'); ?>

        <audio id="audio" src="audios/dashboard.m4a"></audio>

        <script>

            document.getElementById('audioButton').addEventListener('click', function(e){
                e.preventDefault();
                document.getElementById('audio').play();

            })

        </script>
    </body>
</html>