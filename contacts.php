<!DOCTYPE html>
<html lang="en">

    <?php 
        session_start();

        include("includes/translation.php");

        $pageTitle = trad("Contacts");

        include("includes/head.php");
        include("includes/headerMinimalist.php");

        $errorMessages = [

            "missing_field" => trad("Champ manquant, veillez à bien remplir l'ensemble des 3 champs."),
            "invalid_email" => trad("Veillez à saisir correctement votre email."),

        ];

        $errorKey = $_GET["error"] ?? null;

        $errorMessage = $errorMessages[$errorKey] ?? null;



        $notif = [

            "message_sent" => trad("Votre message a bien été envoyé, nous vous remercions d'avoir contribué à l'avancée de Silver Happy."),

        ];

        $notifKey = $_GET["notif"] ?? null;

        $successMessage = $notif[$notifKey] ?? null;

    ?>

    <body>
        <main>
            <div class="backgroundPlain" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>">
                <div class="col-7">
                    <form class="row g-3 contactsForm" method="POST" action="http://localhost:8081/contactForm" novalidate>
                        <div class="row">
                            <div class="col-12">
                                <h3><?php echo trad("Nous contacter") ?></h3>
                                <div class="line mb-1"></div>
                                <p><?php echo trad("Une question ou un projet ? Contactez-nous via le formulaire, notre équipe prendre en compte votre message rapidement.") ?></p>
                            </div>

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

                            <div class="col-6 mb-3 mt-3">
                                <label class="form-label"><?php echo trad("Sujet") ?></label>
                                <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="subject" required> 
                            </div>
                        </div>
                        <?php if(!isset($_SESSION['email']) || $_SESSION['email'] == ""){ ?>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label"><?php echo trad("Email (pour vous répondre ou vous tenir au courant de toute évolution de votre demande)") ?></label>
                                <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend">@</span>  
                                <input type="email" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="email" required>
                                </div>                          
                            </div>
                        </div>
                        <?php }elseif(isset($_SESSION['email']) && $_SESSION['email'] != ""){ ?>
                                <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
                        <?php } ?>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label"><?php echo trad("Message") ?></label>
                                <textarea class="form-control" name="message" maxlength="200" required></textarea>                        
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-5">
                                <button type="submit" class="btn"><?php echo trad("Envoyer") ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-5">
                    <img src="medias/images/imageContacts2.jpg">
                </div>
            </div>
        </main>
    </body>
</html>