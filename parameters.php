<?php

    session_start();

    include("includes/translation.php");

    $pageTitle = trad("Paramètres");
    include('includes/db.php');

    if(isset($_GET['need']) && $_GET['need'] == "call_bdd_back"){

        $dataJson = file_get_contents("http://localhost:8081/showUpdatedData?id=".$_SESSION['id']."&ask=3");

        if($dataJson){

            $response = json_decode($dataJson, true);

            if(isset($response['darkMode']) && isset($response['levelFont']) && isset($response['fontChange']) && isset($response['cursorType'])){

                $_SESSION['darkMode'] = $response['darkMode'];
                $_SESSION['levelFont'] = $response['levelFont'];
                $_SESSION['fontChange'] = $response['fontChange'];
                $_SESSION['cursorType'] = $response['cursorType'];

            }
        }

    }

    include("includes/head.php");

    if(isset($_POST['selectedParameter'])){

        $selectedParameter = (int)$_POST['selectedParameter'];

    }elseif(isset($_GET['selectedParameter'])){

        $selectedParameter = (int)$_GET['selectedParameter'];

    }else{

        $selectedParameter = 0;

    }

    include("includes/headerMinimalist.php");

    if(isset($_POST['step'])){

        $step = (int)$_POST['step'];

    }else{

        $step = 0;

    }

    $errorMessages = [

        "wrong_password" => trad("Mot de passe incorrect."),

    ];

    $errorKey = $_GET["error"] ?? null;

    $errorMessage = $errorMessages[$errorKey] ?? null;    


    $notif = [

        "color_change_successful" => trad("Changement de couleurs effectué."),
        "font_size_changement_success" => trad("Changement de taille d'écriture effectué."),
        "font_style_changement_success" => trad("Changement de police d'écriture effectué."),
        "cursor_type_changement_success" => trad("Changement de curseur effectué."),

    ];

    $notifKey = $_GET["notif"] ?? null;

    $successMessage = $notif[$notifKey] ?? null;
?>

<body>
    <main>
        <div class="backgroundPlain" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;<?php endif;endif; ?>">
            <div class="container">
                <div class="row">
                    <div class="col-8">
                        <div class="backgroundForm mt-3 ms-5" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;color:white;<?php endif;endif; ?>">
                            <h3><?php echo trad("Paramètres") ?></h3>
                            <div class="line mb-2"></div>

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

                            <form method="POST" action="parameters.php">

                                <button type="submit" value="1" class="parameterButton" name="selectedParameter" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>color:white;<?php endif;endif; ?>">
                                    <?php if($_SESSION['darkMode'] == 0){?>
                                    <?php echo trad("Activer le mode sombre") ?> 
                                    <?php }elseif($_SESSION['darkMode'] == 1){?>
                                    <?php echo trad("Activer le mode clair") ?>
                                    <?php } ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                                    </svg>
                                </button>

                                <button type="submit" value="3" class="parameterButton" name="selectedParameter" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>color:white;<?php endif;endif; ?>">
                                    <?php echo trad("Modifiez la taille de l'écriture") ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                                    </svg>
                                </button>

                                <button type="submit" value="4" class="parameterButton" name="selectedParameter" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>color:white;<?php endif;endif; ?>">
                                    <?php echo trad("Modifiez la police d'écriture")?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                                    </svg>
                                </button>

                                <button type="submit" value="5" class="parameterButton" name="selectedParameter" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>color:white;<?php endif;endif; ?>">
                                    <?php echo trad("Modifier le curseur") ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                                    </svg>
                                </button>

                                <button type="submit" value="2" class="parameterButton" name="selectedParameter" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>color:white;<?php endif;endif; ?>">
                                    <?php echo trad("Supprimez votre compte") ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                                    </svg>
                                </button>

                            </form>

                        </div>
                    </div>
                    
                    <div class="col-3">
                        <div class="backgroundForm mt-3 me-5" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;color:white;<?php endif;endif; ?>">
                            <?php if($selectedParameter == 0){ ?>

                                <p><?php echo trad("Sur cette page, vous pouvez personnaliser l’affichage du site afin de le rendre plus confortable et plus agréable à utiliser.") ?></p>
                            
                            <?php }elseif($selectedParameter == 1){ ?>

                                <?php if($_SESSION['darkMode'] == 0): ?>
                                    <form method="POST" action="http://localhost:8081/modifyParameters">
                                        <input type="hidden" name="id" value=<?php echo $_SESSION['id'] ?>> 
                                        <input type="hidden" name="paramChoice" value="1"> 
                                        <input type="hidden" name="colorChange" value="1"> 
                                        <button name="sombre" class="buttonDark">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" class="bi bi-moon-stars-fill" viewBox="0 0 16 16">
                                            <path d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277q.792-.001 1.533-.16a.79.79 0 0 1 .81.316.73.73 0 0 1-.031.893A8.35 8.35 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.75.75 0 0 1 6 .278"/>
                                            <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.73 1.73 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.73 1.73 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.73 1.73 0 0 0 1.097-1.097zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
                                            </svg>
                                        </button>
                                    </form>
                                <?php elseif($_SESSION['darkMode'] == 1): ?>
                                    <form method="POST" action="http://localhost:8081/modifyParameters">
                                        <input type="hidden" name="id" value=<?php echo $_SESSION['id'] ?>> 
                                        <input type="hidden" name="paramChoice" value="1"> 
                                        <input type="hidden" name="colorChange" value="2"> 
                                        <button name="clair" class="buttonClear">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                            </svg>
                                        </button>
                                    </form>
                                <?php endif; ?>

                            <?php }elseif($selectedParameter == 2){ ?>

                                <?php if($step == 0){ ?>

                                    <p><?php echo trad("La suppression de votre compte Silver Happy causera la perte <strong>définitive</strong> de l'ensemble de vos données et la résiliation de votre abonnement. Êtes-vous sûr de vouloir continuer ?") ?></p>

                                    <form method="POST" action="parameters.php">
                                        <input type="hidden" name="selectedParameter" value="2">
                                        <button class="suppressionButton" name="step" value="1"><?php echo trad("Continuer") ?></button>
                                    </form>
                                <?php }elseif($step == 1){ ?>

                                    <form method="POST" action="http://localhost:8081/modifyParameters">
                                        <div class="col-12 mb-3">
                                            <p><?php echo trad("Saisissez votre mot de passe afin de confirmer la suppression du compte") ?></p>
                                            <label class="form-label"><?php echo trad("Mot de passe :") ?></label>
                                            <div class="row text-align-center">
                                                <div class="col-10">
                                                    <input type="password" id="passwordId" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="password" required> 
                                                </div>
                                                <div class="col-2">
                                                    <svg id="eyeId" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="eyeNoShow" viewBox="0 0 16 16">
                                                        <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                                        <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                                        <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <input type="hidden" name="paramChoice" value="2">
                                                <input type="hidden" name="id" value=<?php echo $_SESSION['id'] ?>> 
                                                <button type="submit" class="suppressionButton"><?php echo trad("Supprimer mon compte") ?></button>
                                            </div>
                                        </div>
                                    </form>

                                <?php } ?>
                            <?php }elseif($selectedParameter == 3){ ?>

                                <form method="POST" action="http://localhost:8081/modifyParameters">

                                    <p><?php echo trad("Choisissez une taille d'écriture :") ?></p>

                                    <input type="hidden" name="id" value=<?php echo $_SESSION['id'] ?>>
                                    <input type="hidden" name="paramChoice" value="3">

                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" name="level" value="0" class="btn"><?php echo trad("Classique") ?></button>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <button type="submit" name="level" value="1" class="btn" style="font-size:18px;"><?php echo trad("Niveau n°1") ?></button>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <button type="submit" name="level" value="2" class="btn" style="font-size:19px;"><?php echo trad("Niveau n°2") ?></button>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <button type="submit" name="level" value="3" class="btn" style="font-size:20px;"><?php echo trad("Niveau n°3") ?></button>
                                        </div>
                                    </div>

                                </form>

                            <?php }elseif($selectedParameter == 4){ ?>

                                    <?php if($_SESSION['fontChange'] == 0){ ?>

                                        <p><?php echo trad("Cliquez pour passer à la police Atkinson Hyperlegible :") ?></p>

                                        <form method="POST" action="http://localhost:8081/modifyParameters">

                                            <input type="hidden" name="id" value=<?php echo $_SESSION['id'] ?>>
                                            <input type="hidden" name="paramChoice" value="4">

                                            <div class="row mt-4">
                                                <div class="col-12">
                                                    <button type="submit" name="fontChange" value="0" class="btn" style="font-size:20px;"><?php echo trad("Oui") ?></button>
                                                </div>
                                            </div>

                                        </form>

                                    <?php }elseif($_SESSION['fontChange'] == 1){ ?>

                                        <p><?php echo trad("Cliquez pour passer à la police Montserrat :") ?></p>

                                        <form method="POST" action="http://localhost:8081/modifyParameters">

                                            <input type="hidden" name="id" value=<?php echo $_SESSION['id'] ?>>
                                            <input type="hidden" name="paramChoice" value="4">

                                            <div class="row mt-4">
                                                <div class="col-12">
                                                    <button type="submit" name="fontChange" value="1" class="btn" style="font-size:20px;"><?php echo trad("Oui") ?></button>
                                                </div>
                                            </div>

                                        </form>

                                    <?php } ?>

                            <?php }elseif($selectedParameter == 5){ ?>

                                <?php if($_SESSION['cursorType'] == 0){ ?>

                                    <p><?php echo trad("Cliquez pour passer au curseur haute visibilité :") ?></p>

                                    <form method="POST" action="http://localhost:8081/modifyParameters">

                                        <input type="hidden" name="id" value=<?php echo $_SESSION['id'] ?>>
                                        <input type="hidden" name="paramChoice" value="5">

                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <button type="submit" name="cursorType" value="1" class="btn" style="font-size:20px;"><?php echo trad("Agrandir") ?></button>
                                            </div>
                                        </div>

                                    </form>

                                <?php }elseif($_SESSION['cursorType'] == 1){ ?>

                                    <p><?php echo trad("Cliquez pour passer au curseur classique :") ?></p>

                                    <form method="POST" action="http://localhost:8081/modifyParameters">

                                        <input type="hidden" name="id" value=<?php echo $_SESSION['id'] ?>>
                                        <input type="hidden" name="paramChoice" value="5">

                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <button type="submit" name="cursorType" value="0" class="btn" style="font-size:20px;"><?php echo trad("Rétrécir") ?></button>
                                            </div>
                                        </div>

                                    </form>
                                <?php } ?>

                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script src="jsFunctions/showPassword.js"></script>
    </body>