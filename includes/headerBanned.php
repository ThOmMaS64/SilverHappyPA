<header style="<?php if(isset($_SESSION['id'])):if(isset($_SESSION['darkMode']) && $_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>">
        <nav class="navbar navbar-expand-xl">
            <div class="container-fluid">

                <a href="indexBanned.php"><img src="medias/logos/logoPourFondBleu2.png" alt="Logo Silver Happy" width="160px" class="ms-3 p-1"></a>

                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                    </svg>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if(!empty($_SESSION['profilePicture'])){ ?>

                            <img src="data/profils/<?php echo htmlspecialchars($_SESSION['profilePicture']); ?>" alt="Photo de profil" class="headerPicture">

                        <?php }else{ ?>

                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                            </svg>

                        <?php } ?>

                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="contacts.php">Contacts</a></li>
                        <li><a class="dropdown-item" href="parameters.php">Paramètres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="deconnexion.php">Se déconnecter</a></li>
                    </ul>
                    </li>
                </ul>
                </div>
            </div>
        </nav>
</header>