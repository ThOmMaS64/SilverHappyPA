<!DOCTYPE html>
<html lang="en">

    <?php 
        $pageTitle = "Connexion";

        include("includes/head.php"); 
        include("includes/headerMinimalist.php");

        $errorMessages = [

            "not_registered" => "Nom d'utilisateur inexistant, veuillez-vous inscrire.",
            "wrong_password" => "Identifiants incorrects, veuillez réessayer.",
            "missing_field" => "Champ manquant, veillez à bien saisir votre nom d'utilisateur/email et mot de passe.",
            "bad_registration" => "Vous n'avez pas abouti votre inscription, veuillez la reprendre du début avant de vous connecter.",
            "retry_reset" => "Il se peut que le lien ait été défaillant ou a expiré, veuillez réessayer."

        ];

        $errorKey = $_GET["error"] ?? null;

        $errorMessage = $errorMessages[$errorKey] ?? null;



        $notif = [

            "registration_success" => "Inscription réussie, connectez-vous !",
            "reset_success" => "Réinitialisation du mot de passe réussie, connectez-vous !",
            "forgottentPasswordEmailSent" => "Un email de réinitialisation de mot de passe vous a été envoyé à l'adresse que vous avez saisi."

        ];

        $notifKey = $_GET["notif"] ?? null;

        $successMessage = $notif[$notifKey] ?? null;

    ?>

    <body>
        <main>
            <div class="backgroundPlain">
                <div class="col-4 backgroundForm">
                    <form class="row g-3 needs-validation" method="POST" action="http://localhost:8081/login" novalidate>
                        <div class="row">
                            <div class="col-12">
                                <h3>Connectez-vous</h3>
                                <div class="line mb-4"></div>
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

                            <div class="col-12 mb-3">
                                <label class="form-label">Nom d'utilisateur ou email</label>
                                <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="username" required> 
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Mot de passe</label>
                            <div class="row align-items-center">
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
                                <button type="submit" class="btn">Se connecter</button>
                                <a href="inscription.php" class="mt-3" ><p><strong> Pas de compte Silver Happy ? Créez en un !</strong></p></a>
                                <a href="passwordForgotten.php"><p>Mot de passe oublié ? Cliquez ici !</p></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <script src="jsFunctions/showPassword.js"></script>
    </body>
</html>