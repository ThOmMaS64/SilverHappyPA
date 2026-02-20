<!DOCTYPE html>
<html lang="en">

    <?php 
        session_start();

        include("includes/translation.php");

        $pageTitle = trad("Inscription");

        include("includes/head.php"); 
        include("includes/headerMinimalist.php");

        $q = 'SELECT count(*) FROM CAPTCHA';
        $statement = $bdd->prepare($q);
        $statement->execute([]);
        $nbLignes = $statement->fetchColumn();

        $numQuestion = rand(1, $nbLignes);

        $q = 'SELECT ID_CAPTCHA, question, answer FROM CAPTCHA WHERE ID_CAPTCHA = :numQuestion';
        $statement = $bdd->prepare($q);
        $statement->execute(['numQuestion' => $numQuestion]);
        $captcha = $statement->fetch(PDO::FETCH_ASSOC);

        $choice = isset($_GET['choice']) ? (int)$_GET['choice'] : 0;

        $errorMessages = [

            "bad_password" => "Le mot de passe doit faire au moins 8 caractères de longs et posséder au moins une majuscule et un chiffre.",
            "wrong_password_confirmation" => "Le mot de passe et sa confirmation sont différents.",
            "missing_field" => "Champ manquant, veillez à bien remplir l'ensemble des champs.",
            "invalid_email" => "L'email est invalide, veuillez suivre le format correct.",
            "username_already_exists" => "Ce nom d'utilisateur est déjà attribué à un compte.",
            "email_already_exists" => "Cet email est déjà attribué à un compte.",
            "wrong_captcha" => "La réponse au Captcha est incorrecte.",
            "bad_birthdate" => "Veuillez saisir une date de naissance cohérente.",
            "young" => "Tu dois avoir au moins 14 ans pour t'inscrire."

        ];

        $errorKey = $_GET["error"] ?? null;

        $errorMessage = $errorMessages[$errorKey] ?? null;
        
    ?>

    <body>
        <main style="padding-top:40px;">

            <?php if($choice == 0){ ?>

            <div class="backgroundPlain">
                <div class="row ms-5 choiceImageContainer">
                    <div class="col-12">
                        <h2><?php echo trad("Êtes-vous un :") ?></h2>
                        <div class="line"></div>
                    </div>
                    <div class="col-6 pe-5">
                        <p><?php echo trad("Adhérant") ?></p>
                        <img src="medias/images/adherant.jpg"  width="260px" class="choiceImage" onclick="window.location.href='?choice=1'"> 
                    </div>
                    <div class="col-6 ps-5">
                        <p><?php echo trad("Prestataire") ?></p>
                        <img src="medias/images/prestataire.png"  width="260px" class="choiceImage" onclick="window.location.href='?choice=2'">
                    </div>
                </div>
            </div>

            <?php }else if($choice == 1){ ?>

                <div class="backgroundPlain">
                    <div class="col-8 backgroundForm mt-5">
                        <form class="row g-3 needs-validation" method="POST" action="http://localhost:8081/registrationCustomer" novalidate>
                            <div class="row">
                                <div class="col-12">
                                    <h3><?php echo trad("Inscrivez-vous") ?></h3>
                                    <div class="line mb-2"></div>
                                    <p><?php echo trad("Devenez adhérant chez Silver Happy") ?></p>
                                </div>

                                <div class="col-12">
                                    <?php if (isset($errorMessage)): ?>
                                        <div class="alert alert-danger">
                                            <?php echo htmlspecialchars($errorMessage); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-4 mb-3">
                                    <label class="form-label"><?php echo trad("Nom d'utilisateur") ?></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="username" required>
                                </div>
                                <div class="col-4 mb-4">
                                    <label class="form-label"><?php echo trad("Nom") ?></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>"  name="name" required>
                                </div>
                                <div class="col-4 mb-4">
                                    <label class="form-label"><?php echo trad("Prénom") ?></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>"  name="surname" required>
                                </div>
                            </div> 

                            <div class="row">
                                <div class="col-4 mb-4">
                                    <label class="form-label"><?php echo trad("Mot de passe") ?></label>
                                    <div class="row text-align-center">
                                        <div class="col-10">
                                            <input type="password" id="passwordId" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>"  name="password" required>
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

                                <div class="col-4 mb-4">
                                    <label class="form-label"><?php echo trad("Confirmation du mot de passe") ?></label>
                                    <div class="row text-align-center">
                                        <div class="col-10">
                                            <input type="password" id="passwordId2" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>"  name="passwordConfirmation" required>
                                        </div>
                                        <div class="col-2">
                                            <svg id="eyeId2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="eyeNoShow" viewBox="0 0 16 16">
                                                <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                                <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                                <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                                            </svg>
                                        </div>                                            
                                    </div>
                                </div>

                                <div class="col-4">
                                    <label class="form-label"><?php echo trad("Adresse mail") ?></label>
                                    <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                    <input type="email" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" id="validationCustomUsername" aria-describedby="inputGroupPrepend" name="email" required>
                                    </div>
                                </div>
                            </div> 

                            <div class="row mb-4">
                                <div class="col-3">
                                    <label class="form-label"><?php echo trad("Naissance") ?></label>
                                    <input type="date" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="dateNaissance" required>
                                </div>
                                <div class="col-2">
                                    <label class="form-label"><?php echo trad("Ville") ?></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="ville" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label"><?php echo trad("Rue") ?></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="rue" required> 
                                </div>
                                <div class="col-2">
                                    <label class="form-label"><?php echo trad("Numéro de rue") ?></label>
                                    <input type="number" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="numero" required> 
                                </div>
                                <div class="col-2">
                                    <label class="form-label"><?php echo trad("Code postal") ?></label>
                                    <input type="number" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="postalCode" required> 
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <label class="form-label"><?php echo trad("Vérification de sécurité : ")?> <strong><?php echo trad($captcha['question']); ?></strong></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="captcha_response" placeholder="Saisissez votre réponse ici" required>
                                    <input type="hidden" name="captcha_id" value="<?php echo $captcha['ID_CAPTCHA']; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn"><?php echo trad("S'inscrire") ?></button>
                                    <a href="connexion.php"><p class="mt-3"><?php echo trad("Retourner à la page connexion") ?></p></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            <?php }else if($choice == 2){ ?>

                <div class="backgroundPlain">
                    <div class="col-11 backgroundForm mt-5">
                        <form class="row g-3 needs-validation" method="POST" action="http://localhost:8081/registrationProvider" enctype="multipart/form-data" novalidate>
                            <div class="row">
                                <div class="col-12">
                                    <h3><?php echo trad("Inscrivez-vous") ?></h3>
                                    <div class="line mb-2"></div>
                                    <p><?php echo trad("Devenez prestataire chez Silver Happy") ?></p>
                                </div>

                                <div class="col-12">
                                    <?php if (isset($errorMessage)): ?>
                                        <div class="alert alert-danger">
                                            <?php echo htmlspecialchars($errorMessage); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-3 mb-3">
                                    <label class="form-label"><?php echo trad("Nom d'utilisateur") ?></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="username" required>
                                </div>
                                <div class="col-3 mb-4">
                                    <label class="form-label"><?php echo trad("Nom") ?></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>"  name="name" required>
                                </div>
                                <div class="col-3 mb-4">
                                    <label class="form-label"><?php echo trad("Prénom") ?></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>"  name="surname" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label"><?php echo trad("Adresse mail") ?></label>
                                    <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                    <input type="email" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" name="email" required>
                                    </div>
                                </div>
                            </div> 

                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label"><?php echo trad("Profession/Prestation") ?></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="profession" required> 
                                </div>
                                
                                <div class="col-3 mb-4">
                                    <label class="form-label"><?php echo trad("Mot de passe") ?></label>
                                    <div class="row text-align-center">
                                        <div class="col-10">
                                            <input type="password" id="passwordId" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>"  name="password" required>
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

                                <div class="col-3 mb-4">
                                    <label class="form-label"><?php echo trad("Confirmation du mot de passe") ?></label>
                                    <div class="row text-align-center">
                                        <div class="col-10">
                                            <input type="password" id="passwordId2" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>"  name="passwordConfirmation" required>
                                        </div>
                                        <div class="col-2">
                                            <svg id="eyeId2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="eyeNoShow" viewBox="0 0 16 16">
                                                <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                                <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                                <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                                            </svg>
                                        </div>                                            
                                    </div>
                                </div>

                                <div class="col-3">
                                    <label class="form-label"><?php echo trad("Ville") ?></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="ville" required>
                                </div>
                            </div> 

                            <div class="row mb-4">
                                <div class="col-2">
                                    <label class="form-label"><?php echo trad("Rue") ?></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="rue" required> 
                                </div>
                                <div class="col-2">
                                    <label class="form-label"><?php echo trad("Numéro de rue") ?></label>
                                    <input type="number" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="numero" required> 
                                </div>
                                <div class="col-2">
                                    <label class="form-label"><?php echo trad("Code postal") ?></label>
                                    <input type="number" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="postalCode" required> 
                                </div>
                                <div class="col-2">
                                    <label class="form-label"><?php echo trad("Diplôme") ?></label>
                                    <input type="file" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="diploma" required> 
                                </div>
                                <div class="col-2">
                                    <label class="form-label"><?php echo trad("Casier judiciaire") ?></label>
                                    <input type="file" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="criminalRecord" required> 
                                </div>
                                <div class="col-2">
                                    <label class="form-label"><?php echo trad("Lettre de recommendation") ?></label>
                                    <input type="file" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="recommendationLetter" required> 
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <label class="form-label"><?php echo trad("Vérification de sécurité : ")?> <strong><?php echo trad($captcha['question']); ?></strong></label>
                                    <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="captcha_response" placeholder="Saisissez votre réponse ici" required>
                                    <input type="hidden" name="captcha_id" value="<?php echo $captcha['ID_CAPTCHA']; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn"><?php echo trad("S'inscrire") ?></button>
                                    <a href="connexion.php"><p class="mt-3"><?php echo trad("Retourner à la page connexion") ?></p></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            <?php } ?>
        </main>
        <script src="jsFunctions/showPassword.js"></script>
    </body>
</html>