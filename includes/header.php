<?php $state = isset($_GET['state']) ? $_GET['state'] : null; ?>

<header>
    <?php if(isset($state) && ($state == 1 || $state == 2 || $state == 5 || $state == 6)){ ?>

        <nav class="navbar navbar-expand-xl">
            <div class="container-fluid">

                <a href="index.php?state=<?php echo $state ?>"><img src="medias/logos/logoPourFondBleu2.png" alt="Logo Silver Happy" width="160px" class="ms-3 p-1"></a>

                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                    </svg>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="services.php?state=<?php echo $state ?>">Nos services</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="events.php">Événements</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="store.php">Boutique</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="advices.php?state=<?php echo $state ?>">Conseils</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="messaging.php?state=<?php echo $state ?>">Messagerie/téléconsultation</a>
                    </li>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                        </svg>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php?state=<?php echo $state ?>">Mon profil</a></li>
                        <li><a class="dropdown-item" href="contacts.php?state=<?php echo $state ?>">Contacts</a></li>
                        <li><a class="dropdown-item" href="parameters.php?state=<?php echo $state ?>">Paramètres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="deconnexion.php">Se déconnecter</a></li>
                    </ul>
                    </li>

                </ul>
                </div>
            </div>
        </nav>

    <?php }else if(isset($state) && $state == 4){ ?>

        <nav class="navbar navbar-expand-xl">
            <div class="container-fluid">

                <a href="index.php?state=<?php echo $state ?>"><img src="medias/logos/logoPourFondBleu2.png" alt="Logo Silver Happy" width="160px" class="ms-3 p-1"></a>

                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                    </svg>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="dashboard.php?state=<?php echo $state ?>">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="planning.php?state=<?php echo $state ?>">Mon planning</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="messaging.php?state=<?php echo $state ?>">Messagerie/téléconsultation</a>
                    </li>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                        </svg>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php?state=<?php echo $state ?>">Mon profil</a></li>
                        <li><a class="dropdown-item" href="contacts.php?state=<?php echo $state ?>">Contacts</a></li>
                        <li><a class="dropdown-item" href="parameters.php?state=<?php echo $state ?>">Paramètres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="deconnexion.php">Se déconnecter</a></li>
                    </ul>
                    </li>

                </ul>
                </div>
            </div>
        </nav>

    <?php }else if(!isset($state) || ($state != 1 && $state != 2 && $state != 4 && $state != 5 && $state != 6)){ ?>

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
                    <a class="nav-link active" aria-current="page" href="services.php">Nos services</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="events.php">Événements</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="store.php">Boutique</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="advices.php?state=<?php echo $state ?>">Conseils</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="messaging.php">Messagerie/téléconsultation</a>
                    </li>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                        </svg>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="contacts.php">Contacts</a></li>
                        <li><a class="dropdown-item" href="parameters.php">Paramètres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="connexion.php">Connexion</a></li>
                    </ul>
                    </li>

                </ul>
                </div>
            </div>
        </nav>

    <?php } ?>

</header>