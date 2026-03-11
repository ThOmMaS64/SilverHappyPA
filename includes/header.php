<?php 

    if(isset($_GET['notif']) && $_GET['notif'] == "profile_picture_changement_success"){

        $dataJson = file_get_contents("http://localhost:8081/showUpdatedData?id=".$_SESSION['id']."&ask=1");

        if($dataJson){

            $response = json_decode($dataJson, true);

            if(isset($response['profile_picture'])){

                $_SESSION['profilePicture'] = $response['profilePicture'];

            }
        }

    }

    if(isset($_SESSION['personalizeInputs']) && $pageTitle != "Mon profil"){
        unset($_SESSION['personalizeInputs']);
    }

?>

<header style="<?php if(isset($_SESSION['id'])):if(isset($_SESSION['darkMode']) && $_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>">
    <?php if(isset($_SESSION['status']) && ($_SESSION['status'] == 1 || $_SESSION['status'] == 2 || $_SESSION['status'] == 5 || $_SESSION['status'] == 6)){ ?>

        <nav class="navbar navbar-expand-xl">
            <div class="container-fluid">

                <a href="index.php"><img src="medias/logos/logoPourFondBleu2.png" alt="Logo Silver Happy" width="160px" class="ms-3 p-1"></a>

                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                    </svg>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="services.php"><?php echo trad("Nos services") ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="events.php"><?php echo trad("Événements") ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="store.php"><?php echo trad("Boutique") ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="advices.php"><?php echo trad("Conseils") ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="messaging.php"><?php echo trad("Messagerie/téléconsultation") ?></a>
                    </li>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if(!empty($_SESSION['profilePicture'])){ ?>

                            <img src="imagesData/profils/<?php echo htmlspecialchars($_SESSION['profilePicture']); ?>" alt="Photo de profil" class="headerPicture">

                        <?php }else{ ?>

                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                            </svg>

                        <?php } ?>

                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php"><?php echo trad("Mon profil") ?></a></li>
                        <li><a class="dropdown-item" href="contacts.php"><?php echo trad("Contacts") ?></a></li>
                        <li><a class="dropdown-item" href="parameters.php"><?php echo trad("Paramètres<") ?>/a></li>
                        <li>
                            <div class="row selectLanguageBox">
                                <div class="col-1 ms-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-translate" viewBox="0 0 16 16">
                                        <path d="M4.545 6.714 4.11 8H3l1.862-5h1.284L8 8H6.833l-.435-1.286zm1.634-.736L5.5 3.956h-.049l-.679 2.022z"/>
                                        <path d="M0 2a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v3h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-3H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zm7.138 9.995q.289.451.63.846c-.748.575-1.673 1.001-2.768 1.292.178.217.451.635.555.867 1.125-.359 2.08-.844 2.886-1.494.777.665 1.739 1.165 2.93 1.472.133-.254.414-.673.629-.89-1.125-.253-2.057-.694-2.82-1.284.681-.747 1.222-1.651 1.621-2.757H14V8h-3v1.047h.765c-.318.844-.74 1.546-1.272 2.13a6 6 0 0 1-.415-.492 2 2 0 0 1-.94.31"/>
                                    </svg>
                                </div>             
                                <div class="col-8">
                                    <form method="POST" action="http://localhost:8081/changeLanguage">
                                        <input type="hidden" name="id" value=<?php echo $_SESSION['id'] ?>>
                                        <select name="language" id="language-select" class="selectLanguage" onchange="this.form.submit()">
                                            <option value="" disabled selected>Choisissez</option>
                                            <option value="ar">Arabe</option>
                                            <option value="az">Azerbaïdjanais</option>
                                            <option value="ca">Catalan</option>
                                            <option value="zh">Chinois</option>
                                            <option value="cs">Tchèque</option>
                                            <option value="da">Danois</option>
                                            <option value="nl">Néerlandais</option>
                                            <option value="en">Anglais</option>
                                            <option value="eo">Espéranto</option>
                                            <option value="fi">Finnois</option>
                                            <option value="fr">Français</option>
                                            <option value="de">Allemand</option>
                                            <option value="el">Grec</option>
                                            <option value="he">Hébreu</option>
                                            <option value="hi">Hindi</option>
                                            <option value="hu">Hongrois</option>
                                            <option value="id">Indonésien</option>
                                            <option value="ga">Irlandais</option>
                                            <option value="it">Italien</option>
                                            <option value="ja">Japonais</option>
                                            <option value="ko">Coréen</option>
                                            <option value="lv">Letton</option>
                                            <option value="lt">Lituanien</option>
                                            <option value="ms">Malais</option>
                                            <option value="nb">Norvégien</option>
                                            <option value="fa">Persan</option>
                                            <option value="pl">Polonais</option>
                                            <option value="pt">Portugais</option>
                                            <option value="ro">Roumain</option>
                                            <option value="ru">Russe</option>
                                            <option value="sk">Slovaque</option>
                                            <option value="sl">Slovène</option>
                                            <option value="es">Espagnol</option>
                                            <option value="sv">Suédois</option>
                                            <option value="th">Thaï</option>
                                            <option value="tr">Turc</option>
                                            <option value="uk">Ukrainien</option>
                                            <option value="vi">Vietnamien</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="deconnexion.php"><?php echo trad("Se déconnecter") ?></a></li>
                    </ul>
                    </li>

                </ul>
                </div>
            </div>
        </nav>

    <?php }else if(isset($_SESSION['status']) && $_SESSION['status'] == 4){ ?>

        <nav class="navbar navbar-expand-xl">
            <div class="container-fluid">

                <a href="index.php"><img src="medias/logos/logoPourFondBleu2.png" alt="Logo Silver Happy" width="160px" class="ms-3 p-1"></a>

                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                    </svg>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="dashboard.php"><?php echo trad("Tableau de bord") ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="planning.php"><?php echo trad("Mon planning") ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="messaging.php"><?php echo trad("Messagerie/téléconsultation") ?></a>
                    </li>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if(!empty($_SESSION['profilePicture'])){ ?>

                            <img src="imagesData/profils/<?php echo htmlspecialchars($_SESSION['profilePicture']); ?>" alt="Photo de profil" class="headerPicture">

                        <?php }else{ ?>

                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                            </svg>

                        <?php } ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php"><?php echo trad("Mon profil") ?></a></li>
                        <li><a class="dropdown-item" href="contacts.php"><?php echo trad("Contacts") ?></a></li>
                        <li><a class="dropdown-item" href="parameters.php"><?php echo trad("Paramètres") ?></a></li>
                        <li>
                            <div class="row selectLanguageBox">
                                <div class="col-1 ms-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-translate" viewBox="0 0 16 16">
                                        <path d="M4.545 6.714 4.11 8H3l1.862-5h1.284L8 8H6.833l-.435-1.286zm1.634-.736L5.5 3.956h-.049l-.679 2.022z"/>
                                        <path d="M0 2a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v3h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-3H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zm7.138 9.995q.289.451.63.846c-.748.575-1.673 1.001-2.768 1.292.178.217.451.635.555.867 1.125-.359 2.08-.844 2.886-1.494.777.665 1.739 1.165 2.93 1.472.133-.254.414-.673.629-.89-1.125-.253-2.057-.694-2.82-1.284.681-.747 1.222-1.651 1.621-2.757H14V8h-3v1.047h.765c-.318.844-.74 1.546-1.272 2.13a6 6 0 0 1-.415-.492 2 2 0 0 1-.94.31"/>
                                    </svg>
                                </div>             
                                <div class="col-8">
                                    <form method="POST" action="http://localhost:8081/changeLanguage">
                                        <input type="hidden" name="id" value=<?php echo $_SESSION['id'] ?>>
                                        <select name="language" id="language-select" class="selectLanguage" onchange="this.form.submit()">
                                            <option value="" disabled selected>Choisissez</option>
                                            <option value="ar">Arabe</option>
                                            <option value="az">Azerbaïdjanais</option>
                                            <option value="ca">Catalan</option>
                                            <option value="zh">Chinois</option>
                                            <option value="cs">Tchèque</option>
                                            <option value="da">Danois</option>
                                            <option value="nl">Néerlandais</option>
                                            <option value="en">Anglais</option>
                                            <option value="eo">Espéranto</option>
                                            <option value="fi">Finnois</option>
                                            <option value="fr">Français</option>
                                            <option value="de">Allemand</option>
                                            <option value="el">Grec</option>
                                            <option value="he">Hébreu</option>
                                            <option value="hi">Hindi</option>
                                            <option value="hu">Hongrois</option>
                                            <option value="id">Indonésien</option>
                                            <option value="ga">Irlandais</option>
                                            <option value="it">Italien</option>
                                            <option value="ja">Japonais</option>
                                            <option value="ko">Coréen</option>
                                            <option value="lv">Letton</option>
                                            <option value="lt">Lituanien</option>
                                            <option value="ms">Malais</option>
                                            <option value="nb">Norvégien</option>
                                            <option value="fa">Persan</option>
                                            <option value="pl">Polonais</option>
                                            <option value="pt">Portugais</option>
                                            <option value="ro">Roumain</option>
                                            <option value="ru">Russe</option>
                                            <option value="sk">Slovaque</option>
                                            <option value="sl">Slovène</option>
                                            <option value="es">Espagnol</option>
                                            <option value="sv">Suédois</option>
                                            <option value="th">Thaï</option>
                                            <option value="tr">Turc</option>
                                            <option value="uk">Ukrainien</option>
                                            <option value="vi">Vietnamien</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="deconnexion.php"><?php echo trad("Se déconnecter") ?></a></li>
                    </ul>
                    </li>

                </ul>
                </div>
            </div>
        </nav>

    <?php }else if(!isset($_SESSION['status']) || ($_SESSION['status'] != 1 && $_SESSION['status'] != 2 && $_SESSION['status'] != 4 && $_SESSION['status'] != 5 && $_SESSION['status'] != 6)){ ?>

        <nav class="navbar navbar-expand-xl">
            <div class="container-fluid">

                <a href="index.php"><img src="medias/logos/logoPourFondBleu2.png" alt="Logo Silver Happy" width="160px" class="ms-3 p-1"></a>

                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                    </svg>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="services.php"><?php echo trad("Nos services") ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="events.php"><?php echo trad("Événements") ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="store.php"><?php echo trad("Boutique") ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="advices.php"><?php echo trad("Conseils") ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="messaging.php"><?php echo trad("Messagerie/téléconsultation") ?></a>
                    </li>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                        </svg>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="contacts.php"><?php echo trad("Contacts") ?></a></li>
                        <li>
                            <div class="row selectLanguageBox">
                                <div class="col-1 ms-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-translate" viewBox="0 0 16 16">
                                        <path d="M4.545 6.714 4.11 8H3l1.862-5h1.284L8 8H6.833l-.435-1.286zm1.634-.736L5.5 3.956h-.049l-.679 2.022z"/>
                                        <path d="M0 2a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v3h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-3H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zm7.138 9.995q.289.451.63.846c-.748.575-1.673 1.001-2.768 1.292.178.217.451.635.555.867 1.125-.359 2.08-.844 2.886-1.494.777.665 1.739 1.165 2.93 1.472.133-.254.414-.673.629-.89-1.125-.253-2.057-.694-2.82-1.284.681-.747 1.222-1.651 1.621-2.757H14V8h-3v1.047h.765c-.318.844-.74 1.546-1.272 2.13a6 6 0 0 1-.415-.492 2 2 0 0 1-.94.31"/>
                                    </svg>
                                </div>             
                                <div class="col-8">
                                    <form method="POST" action="index.php?language_changement_request=without_connexion">
                                        <select name="language" id="language-select" class="selectLanguage" onchange="this.form.submit()">
                                            <option value="" disabled selected>Choisissez</option>
                                            <option value="ar">Arabe</option>
                                            <option value="az">Azerbaïdjanais</option>
                                            <option value="ca">Catalan</option>
                                            <option value="zh">Chinois</option>
                                            <option value="cs">Tchèque</option>
                                            <option value="da">Danois</option>
                                            <option value="nl">Néerlandais</option>
                                            <option value="en">Anglais</option>
                                            <option value="eo">Espéranto</option>
                                            <option value="fi">Finnois</option>
                                            <option value="fr">Français</option>
                                            <option value="de">Allemand</option>
                                            <option value="el">Grec</option>
                                            <option value="he">Hébreu</option>
                                            <option value="hi">Hindi</option>
                                            <option value="hu">Hongrois</option>
                                            <option value="id">Indonésien</option>
                                            <option value="ga">Irlandais</option>
                                            <option value="it">Italien</option>
                                            <option value="ja">Japonais</option>
                                            <option value="ko">Coréen</option>
                                            <option value="lv">Letton</option>
                                            <option value="lt">Lituanien</option>
                                            <option value="ms">Malais</option>
                                            <option value="nb">Norvégien</option>
                                            <option value="fa">Persan</option>
                                            <option value="pl">Polonais</option>
                                            <option value="pt">Portugais</option>
                                            <option value="ro">Roumain</option>
                                            <option value="ru">Russe</option>
                                            <option value="sk">Slovaque</option>
                                            <option value="sl">Slovène</option>
                                            <option value="es">Espagnol</option>
                                            <option value="sv">Suédois</option>
                                            <option value="th">Thaï</option>
                                            <option value="tr">Turc</option>
                                            <option value="uk">Ukrainien</option>
                                            <option value="vi">Vietnamien</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="connexion.php"><?php echo trad("Connexion") ?></a></li>
                    </ul>
                    </li>

                </ul>
                </div>
            </div>
        </nav>

    <?php } ?>

</header>