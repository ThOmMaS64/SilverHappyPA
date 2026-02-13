<?php

    $pageTitle = "Paramètres";
    $state = isset($_GET['state']) ? $_GET['state'] : null; 
    include("includes/head.php");
    include("includes/headerMinimalist.php");

?>

<body>
    <main>
        <div class="backgroundPlain">
            <div class="container">
                <div class="row">
                    <div class="col-8">
                        <div class="backgroundForm mt-3 ms-5">
                            <h3>Paramètres</h3>
                            <div class="line mb-4"></div>

                            <div class="accordion-item">
                                <h2 class="accordion-header col-3 mb-3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                        Supprimer votre compte
                                    </button>
                                </h2>
                                <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <p>
                                            La suppression de votre compte sera <strong>définitive</strong>. <br>
                                            Toutes vos données, préférences et historiques seront <strong>effacés de manière permanente</strong> et ne pourront <strong>pas être récupérés</strong>. <br>
                                            Si vous souhaitez simplement faire une pause, pensez à vous déconnecter.<br><br>
                                            Vous êtes certain(e) de vouloir continuer ?
                                        </p>

                                        <a href="#modal" style="text-decoration:none;color:black">
                                            <button type="button" class="btn" id="boutonSup" style="--bs-btn-padding-y: 1rem; --bs-btn-font-size: 1rem; width:auto;">
                                                Oui, je souhaite continuer
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="backgroundForm mt-3 me-5">
                            <p>Sur cette page, vous pouvez personnaliser l’affichage du site afin de le rendre plus confortable et plus agréable à utiliser.<br>Ajustez la taille et la police des caractères, choisissez le mode clair ou sombre, et adaptez l’interface à vos besoins visuels pour une navigation simple, lisible et sereine au quotidien.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>


<div id="modal" class="modal">
  <div class="modal-container">
  <form method="POST" action="http://localhost:8081/suppressionAccount" novalidate>
    <div class="row">
        <h3>Êtes-vous certain(e) de votre choix ?</h3>
        <div class="line2 mb-2 ms-2"></div>
        <p>Ceci est le dernier message préventif. La prochaine étape correspond soit à l'annulation de la suppression, soit à la suppression définitive de votre compte Silver Happy</p>
    </div>
    <div class="row">
        <div class="col-12 mb-4">
            <label class="form-label">Saisissez votre mot de passe afin de supprimer définitivement votre compte</label>
            <input type="password" class="form-control"  name="password" required>
        </div>
    </div>
    <div class="row buttonDelete mt-4 mb-2 text-center">
        <div class="col-12">
            <button class="btn mb-1 me-4" type="submit" name="supprimer" style="color:white;">Supprimer mon compte</button>
            <a href="parameters.php?state=<?php echo $state ?>">Annuler la suppression</a>
        </div>
    </div>
  </form>
</div>