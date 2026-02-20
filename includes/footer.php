<footer style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>">
    <div class="row mt-5">
        <div class="col-4 mt-5 ms-5 mb-5">
            <p><strong><?php echo trad("Notre équipe :")?></strong></p>
            <div class="line mt-1 mb-1"></div>
            <p><?php echo trad("Ali JAHMI — développement, design et gestion de projet,<br>Thomas LARGE — développement, design et gestion de projet,<br>Tristan LE CORRE — développement, design et gestion de projet.") ?></p>
        </div>
        <div class="col-4 mt-5 ms-4 mb-5">
            <p><strong><?php echo trad("Origines du projet :")?></strong></p>
            <div class="line mt-1 mb-1"></div>
            <p><?php echo trad("Silver Happy est né de la volonté de ses fondateurs de replacer l’humain, l’écoute et le bien-être au cœur de l’accompagnement des seniors.")  ?></p>
        </div>
        <div class="col-3 mt-5 ms-5 mb-5">
            <p><strong><?php echo trad("Nous contacter :")?></strong></p>
            <div class="line mt-1 mb-1"></div>
            <strong><a href="mailto:silverhappy@gmail.com"><?php echo trad("silverhappy@gmail.com")?></a></strong>
            <p><br><?php echo trad("(email officiel et professionnel de Silver Happy, pour toute collaboration ou échange professionnel)") ?></p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-4 ms-5 mb-5">
            <p><strong><?php echo trad("Mentions Légales et Crédits :")?></strong></p>
            <div class="line mt-1 mb-1"></div>
            <p><?php echo trad('Icônes par : <a href="https://getbootstrap.com/">Bootstrap</a><br>Images générées par : <a href="https://gemini.google/be/about/?hl=fr">Gemini (Nano Banana)</a>') ?></p>
        </div>
        <div class="col-4 ms-4 mb-5">
            <p><strong><?php echo trad("Votre avis compte !")?></strong></p>
            <div class="line mt-1 mb-1"></div>
            <p><?php echo trad('Faites nous un retour concernant votre expérience sur CarMate !<br>Compliments, conseils ou critiques exprimez vous dans la section <strong><a href="contacts.php">Contacts</a></strong>') ?></p>
        </div>
        <div class="col-3 ms-5 mb-5 mt-3">
            <a href="#top" class="btn-footer">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-arrow-up-circle" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                </svg>
            </a>
        </div>
    </div>
</footer>