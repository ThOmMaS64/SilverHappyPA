<header class="headerMinimalist" style="<?php if(isset($_SESSION['id'])):if(isset($_SESSION['darkMode']) && $_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>">
    <nav class="navbar navbar-expand-xl">
        <div class="container-fluid">
            <a href="<?php if(isset($_SESSION['banned']) && $_SESSION['banned'] == 1){ echo "indexBanned.php"; }else{ echo "index.php"; } ?>">
                <img src="medias/logos/logoPourFondBleu2.png" alt="logo Silver Happy" width="160px" class="p-1">
            </a>
        </div>
    </nav>
</header>