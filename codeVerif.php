<!DOCTYPE html>
<html lang="en">

    <?php 
        session_start();

        include("traitementsPHP/sendCodeVerif.php");

        $pageTitle = "Confirmation email";

        include("includes/head.php"); 
        include("includes/headerMinimalist.php");

        if (isset($_GET['email']) && isset($_GET['name']) && isset($_GET['id']) && isset($_GET['status'])) {

            $email =htmlspecialchars(trim($_GET["email"]));
            $name = htmlspecialchars(trim($_GET["name"]));
            $id = htmlspecialchars(trim($_GET["id"]));
            $status = htmlspecialchars(trim($_GET["status"]));

            if(isset($_SESSION['verif_email']) && $_SESSION['verif_email'] != $email) {

                unset($_SESSION['goodCode']);

            }

            $_SESSION['verif_email'] = $email;

            $_SESSION["id"] = $id;

            if($_SERVER["REQUEST_METHOD"] != "POST" && (!isset($_SESSION['goodCode']) || isset($_GET['resend']))){

                $goodCode = sendCodeFunc($email, $name);

                if($goodCode){
                    $_SESSION['goodCode'] = $goodCode;
                    $successMessage = "Un code vous a bien été envoyé à " . $email .".";
                }else{
                    $errorMessage = "Erreur lors de l'envoie du mail, veuillez réessayer.";
                }

            }


        }

        if($_SERVER["REQUEST_METHOD"] == "POST"){

            if($_POST["code"] == $_SESSION["goodCode"]){

                if($status == -2){

                    header("location:http://traitementsPHP/validRegistrationProvider.php?email=".$email."&name=".$name);
                    exit();

                }elseif($status == -1){

                    header("location:subscribe.php");
                    exit();

                }

            }else{

                $errorMessage = "Le code saisi ne correspond pas au code envoyé.";

            }

        }
    ?>

    <body>
        <main>
            <div class="backgroundPlain">
                <div class="col-4 backgroundForm">
                    <form class="row g-3 needs-validation" method="POST" novalidate>
                        <div class="row">
                            <div class="col-12">
                                <h3>Confirmez votre adresse mail</h3>
                                <div class="line mb-1"></div>
                                <p>Saisissez le code reçut par mail dans le champ ci dessous<p>
                            </div>

                            <div class="col-12">
                                <?php if (isset($errorMessage)): ?>
                                    <div class="alert alert-danger">
                                        <?php echo htmlspecialchars($errorMessage); ?>
                                    </div>
                                <?php elseif (isset($successMessage)): ?>
                                    <div class="alert alert-success">
                                        <?php echo htmlspecialchars($successMessage); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Code</label>
                                <input type="text" class="form-control <?php echo isset($errorMessage) ? 'is-invalid' : ''; ?>" name="code" required> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn mb-1">Confirmer</button>
                                <?php if (isset($_GET['email']) && isset($_GET['name']) && isset($_GET['id'])) { ?>
                                <a href="codeVerif.php?email=<?php echo $email ?>&name=<?php echo $name ?>&id=<?php echo $id ?>&resend=1"><p>renvoyer le mail</p></a>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </body>
</html>