<!DOCTYPE html>
<html lang="en">

    <?php 
        session_start();

        include("includes/translation.php");

        $pageTitle = trad("Mot de passe oublié");

        include("includes/head.php"); 
        include("includes/headerMinimalist.php");

        $errorMessages = [

            "no_account_found" => trad("Aucun compte correspond aux informations saisies n'a été retrouvé. Assurez-vous de l'orthographe. Il se peut également que vous ne soyez pas inscrit."),
            "missing_field" => trad("Champ manquant, veillez à bien saisir votre nom d'utilisateur/adresse email."),

        ];

        $notif = $_GET["notif"] ?? null;

        $errorKey = $_GET["error"] ?? null;

        $errorMessage = $errorMessages[$errorKey] ?? null;
    ?>

    <body>
        <main>
            <div class="backgroundPlain">
                <div class="col-4 backgroundForm">
                    <form class="row g-3 needs-validation" method="POST" action="traitementsPHP/sendEmailForgottenPassword.php" novalidate>
                        <div class="row">
                            <div class="col-12">
                                <h3><?php echo trad("Informations nécessaires") ?></h3>
                                <div class="line mb-1"></div>
                                <p><?php echo trad("Saisissez votre adresse email ou votre nom d'utilisateur") ?></p>
                            </div>

                            <div class="col-12">
                                <?php if (isset($errorMessage)): ?>
                                    <div class="alert alert-danger">
                                        <?php echo htmlspecialchars($errorMessage); ?>
                                    </div>
                                <?php elseif(isset($_SESSION['notif'])): ?>
                                    <div class="alert alert-success">
                                        <?php echo htmlspecialchars($_SESSION['notif']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label"><?php echo trad("Nom d'utilisateur ou email") ?></label>
                                <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="username" required> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn mb-3"><?php echo trad("Recevoir le mail") ?></button>
                                <a href="connexion.php"><p><?php echo trad("Retourner à la page connexion") ?></p></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </body>
</html>