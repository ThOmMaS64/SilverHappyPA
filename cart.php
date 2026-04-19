<?php 
    session_start();
    include('traitementsPHP/deconnexionAuto.php'); ?>
<!DOCTYPE html>
<html lang="en">

    <?php 

        include("includes/translation.php");

        $pageTitle = trad("Mon panier");

        include("includes/head.php");
        include("includes/headerMinimalist.php");

        $dataJson = file_get_contents("http://localhost:8081/showCart?id=". $_SESSION['id']);
            
        $cartList = [];

        if($dataJson){

            $response = json_decode($dataJson, true);

            if(isset($response['error']) && $response['error'] != ""){

                $errorMessage = $response['error'];

            }else{

                $cartList = $response['products'];
                $totalPrice = $response['total_price'];

            }

        }

         $notif = [

            "update_success" => "Mise à jour du panier réussie.",
            "delete_success" => "Suppression réussie.",
            "paiement_success" => "Paiement réussi, retrouvez votre facture sur votre profil."

        ];

        $notifKey = $_GET["notif"] ?? null;

        $successMessage = $notif[$notifKey] ?? null;

        $error = [

            "update_error" => "Erreur lors de la mise à jour du panier.",
            "delete_error" => "Erreur lors de la suppression.",
            "paiement_error" => "Paiement échoué.",
            "invoice_error" => "Erreur lors de la génération de la facture, veuillez contacter nos services via la page Contacts. Votre commande à en revanche bien été prise en compte."

        ];

        $errorKey = $_GET["error"] ?? null;

        $errorMessage = $error[$errorKey] ?? null;

    ?>

    <body>
        <main>
            <div class="backgroundPlain">
                <div class="col-8 backgroundForm">
                        <div class="row">
                            <div class="col-12">
                                <h3><?php echo trad("Votre panier") ?></h3>
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

                            <?php if(!empty($cartList)){ ?>

                                <table class="table table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                        <th scope="col">Produit</th>
                                        <th scope="col">Quantité</th>
                                        <th scope="col">Prix</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($cartList as $item){

                                            $idFormItem = "form_item_" . $item['id_product'];
                                        ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($item['name'] ?? '') ?></td>

                                                    <td><input form="<?= $idFormItem ?>" class="form-control" name="quantity" class="mediumtext" type="number" value="<?= htmlspecialchars($item['quantity'] ?? '') ?>"></td>

                                                    <td><?= htmlspecialchars($item['price'] ?? '') ?>€</td>

                                                    <td>
                                                        <form id="<?= $idFormItem ?>" method="POST" action="http://localhost:8081/updateItemData">
                                                            <button class="btn" type="submit" value="<?= $item['id_product'] ?>">Modifier</button>
                                                            <input type="hidden" name="id_product" value="<?= $item['id_product'] ?>">
                                                            <input type="hidden" name="id" value="<?= $_SESSION['id'] ?>">
                                                        </form>
                                                    </td>

                                                    <td>
                                                        <form method="POST" action="http://localhost:8081/deleteItem">
                                                            <button class="btn" type="submit" value="<?= $item['id_product'] ?>">Supprimer</button>
                                                            <input type="hidden" name="id_product" value="<?= $item['id_product'] ?>">
                                                            <input type="hidden" name="id" value="<?= $_SESSION['id'] ?>">
                                                        </form>
                                                    </td>

                                                </tr>

                                            <?php } ?>
                                    </tbody>
                                </table>

                                <form method ="POST" action="traitementsPHP/checkoutCart.php">
                                    <button class="btn" type="submit">
                                        Paiement
                                    </button>
                                </form>

                            <?php }else{ ?>

                                <p>Votre panier est actuellement vide, n'hésitez pas à jeter un coup d'oeil à la boutique !</p>

                            <?php } ?>
                        </div>
                </div>
            </div>
        </main>
        <?php include('includes/magnifyingLink.php'); ?>
    </body>
</html>