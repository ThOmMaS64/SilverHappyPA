<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="icon" href="../medias/logos/logoDessin.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="style.css?v=4">
    <script src="script.js"></script>
</head>
<?php

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tableChoice'])){

    $_SESSION['tableChoice'] = (int)$_POST['tableChoice'];

}

$research = isset($_GET['research']) ? urlencode($_GET['research']) : "";
$filter = isset($_GET['filter']) ? urlencode($_GET['filter']) : "";
$sort = isset($_GET['sort']) ? urlencode($_GET['sort']) : "";

if(isset($_GET['research']) || isset($_GET['filter']) || isset($_GET['sort'])){

    $dataUsers = @file_get_contents("http://localhost:8081/showUsersPersonalizedData?research=$research&filter=$filter&sort=$sort");

}else{

    $dataUsers = @file_get_contents("http://localhost:8081/showUsersDefaultData");

}

$dataShop = @file_get_contents("http://localhost:8081/backShowShop");
$dataEvent = @file_get_contents("http://localhost:8081/backShowEvents");
$dataTip = @file_get_contents("http://localhost:8081/backShowTips");

if($dataUsers){

    $users = json_decode($dataUsers, true);
    $_SESSION['listUsers'] = array();

    if(is_array($users)){
        foreach($users as $user){
            $registeredUser = [
                'ID_USER' => $user['ID_USER'],
                'username' => $user['username'],
                'name' => $user['name'],
                'surname' => $user['surname'],
                'description' => $user['description'],
                'keyWord1' => $user['keyWord1'],
                'keyWord2' => $user['keyWord2'],
                'keyWord3' => $user['keyWord3'],
                'email' => $user['email'],
                'city' => $user['city'],
                'street' => $user['street'],
                'nb_street' => $user['nb_street'],
                'postal_code' => $user['postal_code'],
                'status' => $user['status'],
                'connected' => $user['connected'],
                'date_inscription' => $user['date_inscription'],
                'last_connection' => $user['lastConnection'],
                'birth_date' => $user['birthDate'],
                'banned' => $user['banned']
            ];
            $_SESSION['listUsers'][] = $registeredUser;
        }
    }
}

if($dataShop){

    $shop = json_decode($dataShop, true);
    $_SESSION['listshop'] = array();

    if(is_array($shop)){
        foreach($shop as $product){
            $registeredProduct = [
                'ID_PRODUCT' => $product['ID_PRODUCT'],
                'name' => $product['name'],
                'type' => $product['type'],
                'description' => $product['description'],
                'price' => $product['price']
            ];
            $_SESSION['listshop'][] = $registeredProduct;
        }
    }
}

if($dataEvent){

    $events = json_decode($dataEvent, true);
    $_SESSION['listevents'] = array();

    if(is_array($events)){
        foreach($events as $event){
            $registeredEvents = [
                'ID_EVENT' => $event['ID_EVENT'],
                'name' => $event['name'],
                'type' => $event['type'],
                'date_' => $event['date_'],
                'description' => $event['description']
            ];
            $_SESSION['listevents'][] = $registeredEvents;
        }
    }
}

if($dataTip){

    $tips = json_decode($dataTip, true);
    $_SESSION['listtips'] = array();

    if(is_array($tips)){
        foreach($tips as $tip){
            $registeredTips = [
                'ID_ADVICE' => $tip['ID_ADVICE'],
                'title' => $tip['title'],
                'theme' => $tip['theme'],
                'description' => $tip['description'],
                'date_publication' => $tip['date_publication']
            ];
            $_SESSION['listtips'][] = $registeredTips;
        }
    }
}
?>
<body onload="loadWelcome()">
    <header>
        <img src="../medias/logos/logoEcriture.png" alt="logo Silver Happy" height="100px">
    </header>
    <main>
        <div class="nav pt-5">
            <ul>
                <li><a href="#pagewelcome" onclick="showWelcome()">Accueil</a></li>

                <form id="connForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="conn">
                </form>

                <form id="userForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="users">
                </form>
                <li><a href="#pageusers" onclick="hideWelcome(); document.getElementById('userForm').submit(); return false;">Gestion des utilisateurs</a></li>

                <form id="shopForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="shop">
                </form>
                <li><a href="#pageshop" onclick="hideWelcome(); document.getElementById('shopForm').submit(); return false;">Gestion de la boutique</a></li>

                <form id="eventsForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="events">
                </form>
                <li><a href="#pageevents" onclick="hideWelcome(); document.getElementById('eventsForm').submit(); return false;">Gestion des événements</a></li>

                <form id="moneyForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="money">
                </form>
                <li><a href="#pageùoney" onclick="hideWelcome(); document.getElementById('moneyForm').submit(); return false;">Gestion financière</a></li>

                <form id="tipsForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="tips">
                </form>
                <li><a href="#pagetips" onclick="hideWelcome(); document.getElementById('tipsForm').submit(); return false;">Gestion des conseils</a></li>

                <form id="notifsForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="notifs">
                </form>
                <li><a href="#pagenotifs" onclick="hideWelcome(); document.getElementById('notifsForm').submit(); return false;">Gestion des notifications</a></li>

                <form id="messagesForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="messages">
                </form>
                <li><a href="#pagemessages" onclick="hideWelcome(); document.getElementById('messagesForm').submit(); return false;">Contrôle des messages</a></li>

                <li><a href="deconnexion.php">Se déconnecter</a></li>
            </ul>
        </div>

        <div class="right">
            <div id="pagewelcome">
                <h1>Back Office Silver Happy</h1>
                <p>Utilisez la navigation à gauche pour accéder aux outils d'administration</p>
                </div>

            <section id="pageusers" class="mt-5">
                <?php if(isset($_SESSION['listUsers'])): ?>

                    <?php $connectedUser = 0; ?>

                    <?php foreach($_SESSION['listUsers'] as $user){

                        if($user['connected'] == 1){

                            $connectedUser += 1;

                        }

                        if(!isset($_SESSION['tableChoice'])){ $_SESSION['tableChoice'] = 0;}

                    } ?>

                <div class="row">
                    <div class="col-5">
                        <h1>Gestion des utilisateurs</h1>
                    </div>
                    <div class="col-2 pt-3">
                        <p>(<?php echo $connectedUser ?> utilisateur.s connecté.s)</p>
                    </div>
                </div>

                <form method="GET" action="index.php#pageusers">
                    <div class="row">
                        <div class="col-2">
                            <div class="input-group">
                                <input value="<?php if(isset($_GET['research'])){ echo htmlspecialchars($_GET['research']); }else{ echo ""; } ?>" class="form-control" name="research" placeholder="<?php if(isset($_GET['research']) && $_GET['research'] != ""){ echo $_GET['research']; }else{ ?><?php echo "Tapez votre recherche"; ?> <?php } ?>" aria-label="Search">
                                <button class="searchButton" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="col-2">
                            <select name="filter" class="selectFilter" onchange="this.form.submit()">
                                <option disabled <?php if(!isset($_GET['filter']) || $_GET['filter'] == ""){echo 'selected';} ?>>Choisissez un filtre</option>
                                <option value="1" <?php if(isset($_GET['filter']) && $_GET['filter'] == "1"){echo 'selected';} ?>>Adhérents uniquement</option>
                                <option value="2" <?php if(isset($_GET['filter']) && $_GET['filter'] == "2"){echo 'selected';} ?>>Prestataires uniquement</option>
                                <option value="3" <?php if(isset($_GET['filter']) && $_GET['filter'] == "3"){echo 'selected';} ?>>Connectés uniquement</option>
                                <option value="4" <?php if(isset($_GET['filter']) && $_GET['filter'] == "4"){echo 'selected';} ?>>Déconnectés uniquement</option>
                            </select>
                        </div>

                        <div class="col-2">
                            <select name="sort" class="selectSort" onchange="this.form.submit()">
                                <option disabled <?php if(!isset($_GET['sort']) || $_GET['sort'] == ""){echo 'selected';} ?>>Choisissez un tri</option>
                                <option value="1" <?php if(isset($_GET['sort']) && $_GET['sort'] == "1"){echo 'selected';} ?>>Plus anciens comptes en premier</option>
                                <option value="2" <?php if(isset($_GET['sort']) && $_GET['sort'] == "2"){echo 'selected';} ?>>Plus récents comptes en premier</option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="col-2 pt-3">
                    <?php if($_SESSION['tableChoice'] == 0){ ?>

                        <form method="POST" action="">
                            <input type="hidden" name="tableChoice" value="1">
                            <button>Affichage expert</button>
                        </form>

                    <?php }else{ ?>

                        <form method="POST" action="">
                            <input type="hidden" name="tableChoice" value="0">
                            <button>Affichage standart</button>
                        </form>

                    <?php } ?>
                </div>

                <br>

                    <?php if($_SESSION['tableChoice'] == 0){ ?>
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom d'utilisateur</th>
                                    <th scope="col">Prénom</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Date de naissance</th>
                                    <th scope="col">Ville</th>
                                    <th scope="col">Rue</th>
                                    <th scope="col">Numéro de rue</th>
                                    <th scope="col">Code Postal</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date d'inscription</th>
                                    <th scope="col">Connexion</th>
                                    <th scope="col">Bannissement</th>
                                    <th scope="col">Sélectionner</th>
                                    <th scope="col">Modifier</th>
                                    <th scope="col">Bannir</th>
                                    <th scope="col">Supprimer</th>
                                    <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach($_SESSION['listUsers'] as $user){ 

                                            $idForm = "form_" . $user['ID_USER'];
                                    ?>
                                        <tr>
                                            <th scope="row"> <?= htmlspecialchars($user['ID_USER']) ?></th>

                                            <td><input form="<?= $idForm ?>" name="username" class="mediumtext" type="text" value="<?= htmlspecialchars($user['username'] ?? '') ?>"></td>
                                                
                                            <td><input form="<?= $idForm ?>" name="name" class="mediumtext" type="text" value="<?= htmlspecialchars($user['name'] ?? '') ?>"></td>
                                            <td><input form="<?= $idForm ?>" name="surname" class="mediumtext" type="text" value="<?= htmlspecialchars($user['surname'] ?? '') ?>"></td>
                                            <td><input form="<?= $idForm ?>" name="email" class="mediumtext" type="text" value="<?= htmlspecialchars($user['email'] ?? '') ?>"></td>
                                            <td><input form="<?= $idForm ?>" name="birth_date" class="mediumtext" type="date" value="<?php if($user['birth_date'] == '1900-01-01'){echo "non renseigné";}else{echo  htmlspecialchars($user['birth_date']);} ?>"></td>
                                                
                                            <td><input form="<?= $idForm ?>" name="city" class="mediumtext" type="text" value="<?= htmlspecialchars($user['city'] ?? '') ?>"></td>
                                            <td><input form="<?= $idForm ?>" name="street" class="mediumtext" type="text" value="<?= htmlspecialchars($user['street'] ?? '') ?>"></td>
                                            <td><input form="<?= $idForm ?>" name="nb_street" class="mediumtext" type="text" value="<?= htmlspecialchars($user['nb_street'] ?? '') ?>"></td>
                                            <td><input form="<?= $idForm ?>" name="postal_code" class="mediumtext" type="text" value="<?= htmlspecialchars($user['postal_code'] ?? '') ?>"></td>

                                            <td><input form="<?= $idForm ?>" name="status" class="mediumtext" type="text" value="<?= htmlspecialchars($user['status'] ?? '') ?>"></td>
                                            <td><input form="<?= $idForm ?>" name="date_inscription" class="mediumtext" type="date" value="<?= htmlspecialchars($user['date_inscription'] ?? '0') ?>"></td>
                                            <td><?php if($user['connected'] == 1){echo "en cours";}else{echo date("d/m/Y", strtotime($user['last_connection']));} ?></td>
                                            <td><?= htmlspecialchars($user['banned'] ?? '') ?></td>

                                            <td>
                                                <div class="form-check">
                                                    <input form="selectedEmail" name="selectedEmail[]" class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($user['email']); ?>">
                                                </div>
                                            </td>

                                            <td>
                                                <form id="<?= $idForm ?>" method="POST" action="http://localhost:8081/updateUsersData">
                                                    <button type="submit" value="<?= $user['ID_USER'] ?>">Modifier</button>
                                                    <input type="hidden" name="id" value="<?= $user['ID_USER'] ?>">
                                                    <input type="hidden" name="description" value="<?= htmlspecialchars($user['description']) ?>">
                                                    <input type="hidden" name="keyWord1" value="<?= htmlspecialchars($user['keyWord1']) ?>">
                                                    <input type="hidden" name="keyWord2" value="<?= htmlspecialchars($user['keyWord2']) ?>">
                                                    <input type="hidden" name="keyWord3" value="<?= htmlspecialchars($user['keyWord3']) ?>">
                                                </form>
                                            </td>

                                            <td>
                                                <form method="POST" action="http://localhost:8081/banUser">
                                                    <button type="submit" value="<?= $user['ID_USER'] ?>"><?php if($user['banned'] == 0){ echo "Bannir"; }else{ echo "Pardonner"; } ?></button>
                                                    <input type="hidden" name="id" value="<?= $user['ID_USER'] ?>">
                                                    <input type="hidden" name="banned" value="<?= $user['banned'] ?>">
                                                </form>
                                            </td>

                                            <td>
                                                <form method="POST" action="http://localhost:8081/deleteUser">
                                                    <button type="submit" value="<?= $user['ID_USER'] ?>">Supprimer</button>
                                                    <input type="hidden" name="id" value="<?= $user['ID_USER'] ?>">
                                                    <input type="hidden" name="status" value="<?= $user['status'] ?>">
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                    <?php }elseif($_SESSION['tableChoice'] == 1){ ?>
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nom d'utilisateur</th>
                                <th scope="col">Prénom</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Email</th>
                                <th scope="col">Date de naissance</th>
                                <th scope="col">Ville</th>
                                <th scope="col">Rue</th>
                                <th scope="col">Numéro de rue</th>
                                <th scope="col">Code Postal</th>
                                <th scope="col">Description</th>
                                <th scope="col">Key word 1</th>
                                <th scope="col">Key word 2</th>
                                <th scope="col">Key word 3</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date d'inscription</th>
                                <th scope="col">Connexion</th>
                                <th scope="col">Bannissement</th>
                                <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                    foreach($_SESSION['listUsers'] as $user){ 

                                        $idForm = "form_" . $user['ID_USER'];
                                    
                                    ?>
                                    <tr>
                                        <th scope="row"> <?= htmlspecialchars($user['ID_USER']) ?></th>

                                        <td><input form="<?= $idForm ?>" name="username" class="mediumtext" type="text" value="<?= htmlspecialchars($user['username'] ?? '') ?>"></td>
                                            
                                        <td><input form="<?= $idForm ?>" name="name" class="mediumtext" type="text" value="<?= htmlspecialchars($user['name'] ?? '') ?>"></td>
                                        <td><input form="<?= $idForm ?>" name="surname" class="mediumtext" type="text" value="<?= htmlspecialchars($user['surname'] ?? '') ?>"></td>
                                        <td><input form="<?= $idForm ?>" name="email" class="mediumtext" type="text" value="<?= htmlspecialchars($user['email'] ?? '') ?>"></td>
                                        <td><input form="<?= $idForm ?>" name="birth_date" class="mediumtext" type="date" value="<?php if($user['birth_date'] == '1900-01-01'){echo "non renseigné";}else{echo  htmlspecialchars($user['birth_date']);} ?>"></td>                                                                                            
                                        <td><input form="<?= $idForm ?>" name="city" class="mediumtext" type="text" value="<?= htmlspecialchars($user['city'] ?? '') ?>"></td>
                                        <td><input form="<?= $idForm ?>" name="street" class="mediumtext" type="text" value="<?= htmlspecialchars($user['street'] ?? '') ?>"></td>
                                        <td><input form="<?= $idForm ?>" name="nb_street" class="mediumtext" type="text" value="<?= htmlspecialchars($user['nb_street'] ?? '') ?>"></td>
                                        <td><input form="<?= $idForm ?>" name="postal_code" class="mediumtext" type="text" value="<?= htmlspecialchars($user['postal_code'] ?? '') ?>"></td>

                                        <td><input form="<?= $idForm ?>" name="description" class="bigtext" type="text" value="<?= htmlspecialchars($user['description'] ?? '') ?>"></td>
                                            
                                        <td><input form="<?= $idForm ?>" name="keyWord1" class="bigtext" type="text" value="<?= htmlspecialchars($user['keyWord1'] ?? '') ?>"></td>
                                        <td><input form="<?= $idForm ?>" name="keyWord2" class="bigtext" type="text" value="<?= htmlspecialchars($user['keyWord2'] ?? '') ?>"></td>
                                        <td><input form="<?= $idForm ?>" name="keyWord3" class="bigtext" type="text" value="<?= htmlspecialchars($user['keyWord3'] ?? '') ?>"></td>

                                        <td><input form="<?= $idForm ?>" name="status" class="bigtext" type="text" value="<?= htmlspecialchars($user['status'] ?? '0') ?>"></td>
                                        <td><input form="<?= $idForm ?>" name="date_inscription" class="mediumtext" type="date" value="<?= htmlspecialchars($user['date_inscription'] ?? '0') ?>"></td>
                                        <td><?php if($user['connected'] == 1){echo "en cours";}else{echo date("d/m/Y", strtotime($user['last_connection']));} ?></td>

                                        <td>
                                            <form id="<?= $idForm ?>" method="POST" action="http://localhost:8081/updateUsersData">
                                                <button type="submit" value="<?= $user['ID_USER'] ?>">Modifier</button>
                                                <input type="hidden" name="id" value="<?= $user['ID_USER'] ?>">
                                            </form>
                                        </td>

                                        <td>
                                            <form method="POST" action="http://localhost:8081/banUser">
                                                <button type="submit" value="<?= $user['ID_USER'] ?>"><?php if($user['banned'] == 0){ echo "Bannir"; }else{ echo "Pardonner"; } ?></button>
                                                <input type="hidden" name="id" value="<?= $user['ID_USER'] ?>">
                                                <input type="hidden" name="banned" value="<?= $user['banned'] ?>">
                                            </form>
                                        </td>

                                        <td>
                                            <form method="POST" action="http://localhost:8081/deleteUser">
                                                <button type="submit" value="<?= $user['ID_USER'] ?>">Supprimer</button>
                                                <input type="hidden" name="id" value="<?= $user['ID_USER'] ?>">
                                                <input type="hidden" name="status" value="<?= $user['status'] ?>">
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pageUsers" value="moins">Précedent</button>
                    <button type="submit" name="pageUsers" value="plus">Suivant</button>
                    <button type="submit" name="pageUsers">Rafraîchir</button>
                </form>
                <?php endif; ?>



                <h5 class="pt-5">Contacter les emails sélectionnés</h5>

                <form id="selectedEmail" method="POST" action="sendEmailSelectedUsers.php">

                    <label>Objet</label>
                    <input type="text" name="subject" class="form-control mb-3" placeholder="Saisissez l'objet de l'email" required>

                    <label>Corps de l'email</label>
                    <textarea type="text" name="mail" class="form-control mb-3" placeholder="Rédigez l'email" required></textarea>

                    <button type="submit">Envoyer</button>

                </form>
            </section>

            <section id="pageshop" class="mt-5">
                <?php if(isset($_SESSION['listshop'])): ?>
                <h1>Liste des articles du magasin</h1>

                <div>
                    <form method="POST" action="traitement_search.php">
                        <input type="text" class="" name="searchvalue" placeholder="Rechercher un élément...">
                        <input type="hidden" name="action" value="shop">
                    </form>
                </div>
                <br>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Type</th>
                        <th scope="col">Description</th>
                        <th scope="col">Prix</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="POST" action="traitement_edit.php">
                            <?php
                                for($i=0; $i<count($_SESSION['listshop']); $i++){
                                    echo '<tr>';
                                        echo '<th scope="row">' . htmlspecialchars($_SESSION['listshop'][$i]['ID_PRODUCT']) . '</th>';

                                        echo '<td><input class="bigtext" type="text" name="newname[' . $_SESSION['listshop'][$i]['ID_PRODUCT'] . ']" value="' . $_SESSION['listshop'][$i]['name'] . '"></td>'; 
                                        
                                        echo '<td><input class="mediumtext" type="text" name="newtype[' . $_SESSION['listshop'][$i]['ID_PRODUCT'] . ']" value="' . $_SESSION['listshop'][$i]['type'] . '"></td>'; 

                                        echo '<td><input class="bigtext" type="text" name="newdescription[' . $_SESSION['listshop'][$i]['ID_PRODUCT'] . ']" value="' . $_SESSION['listshop'][$i]['description'] . '"></td>';

                                        echo '<td><input class="smalltext" type="text" name="newprice[' . $_SESSION['listshop'][$i]['ID_PRODUCT'] . ']" value="' . $_SESSION['listshop'][$i]['price'] . '"></td>'; 

                                        echo '<td><button class="button" type="submit" name="updateshop" value="' . $_SESSION['listshop'][$i]['ID_PRODUCT'] . '">Update</button></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </form>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pageShop" value="moins">Précedent</button>
                    <button type="submit" name="pageShop" value="plus">Suivant</button>
                    <button type="submit" name="pageShop">Rafraîchir</button>
                </form>

                <h2>Ajouter une ligne</h2>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">Nom</th>
                        <th scope="col">Type</th>
                        <th scope="col">Description</th>
                        <th scope="col">Prix</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="POST" action="traitement_add.php">
                            <tr>
                                <td><input class="bigtext" type="text" name="name"></td>

                                <td><input class="mediumtext" type="text" name="type"></td>

                                <td><input class="bigtext" type="text" name="description"></td>

                                <td><input class="smalltext" type="text" name="price"></td>
                                        
                                <td><button class="button" type="submit" name="addproduct">Ajouter</button></td>
                            </tr>
                        </form>
                    </tbody>
                </table>
                <?php endif; ?>
            </section>

            <section id="pageevents" class="mt-5">
                <?php if(isset($_SESSION['listevents'])): ?>
                <h1>Liste des événements</h1>

                <div>
                    <form method="POST" action="traitement_search.php">
                        <input type="text" class="" name="searchvalue" placeholder="Rechercher un élément...">
                        <input type="hidden" name="action" value="events">
                    </form>
                </div>
                <br>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Type</th>
                        <th scope="col">Date</th>
                        <th scope="col">Description</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="POST" action="traitement_edit.php">
                            <?php
                                for($i=0; $i<count($_SESSION['listevents']); $i++){
                                    echo '<tr>';
                                        echo '<th scope="row">' . htmlspecialchars($_SESSION['listevents'][$i]['ID_EVENT']) . '</th>';

                                        echo '<td><input class="mediumtext" type="text" name="newname[' . $_SESSION['listevents'][$i]['ID_EVENT'] . ']" value="' . $_SESSION['listevents'][$i]['name'] . '"></td>'; 

                                        echo '<td><input class="mediumtext" type="text" name="newtype[' . $_SESSION['listevents'][$i]['ID_EVENT'] . ']" value="' . $_SESSION['listevents'][$i]['type'] . '"></td>';
                                        
                                        echo '<td><input class="mediumtext" type="text" name="newdate_[' . $_SESSION['listevents'][$i]['ID_EVENT'] . ']" value="' . $_SESSION['listevents'][$i]['date_'] . '"></td>'; 

                                        echo '<td><input class="bigtext" type="text" name="newdescription[' . $_SESSION['listevents'][$i]['ID_EVENT'] . ']" value="' . $_SESSION['listevents'][$i]['description'] . '"></td>'; 
                                        
                                        echo '<td><button class="button" type="submit" name="updateevent" value="' . $_SESSION['listevents'][$i]['ID_EVENT'] . '">Update</button></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </form>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pageEvents" value="moins">Précedent</button>
                    <button type="submit" name="pageEvents" value="plus">Suivant</button>
                    <button type="submit" name="pageEvents">Rafraîchir</button>
                </form>
                <?php endif; ?>
            </section>

            <section id="pagemoney" class="mt-5">
                
            </section>
            
            <section id="pagetips" class="mt-5">
                <?php if(isset($_SESSION['listtips'])): ?>
                <h1>Liste des conseils</h1>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Thème</th>
                        <th scope="col">Description</th>
                        <th scope="col">Date de publication</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="POST" action="traitement_edit.php">
                            <?php
                                for($i=0; $i<count($_SESSION['listtips']); $i++){
                                    echo '<tr>';
                                        echo '<th scope="row">' . htmlspecialchars($_SESSION['listtips'][$i]['ID_ADVICE']) . '</th>';

                                        echo '<td><input class="mediumtext" type="text" name="newtitle[' . $_SESSION['listtips'][$i]['ID_ADVICE'] . ']" value="' . $_SESSION['listtips'][$i]['title'] . '"></td>'; 

                                        echo '<td><input class="mediumtext" type="text" name="newtheme[' . $_SESSION['listtips'][$i]['ID_ADVICE'] . ']" value="' . $_SESSION['listtips'][$i]['theme'] . '"></td>'; 

                                        echo '<td><input class="bigtext" type="text" name="newdescription[' . $_SESSION['listtips'][$i]['ID_ADVICE'] . ']" value="' . $_SESSION['listtips'][$i]['description'] . '"></td>'; 

                                        echo '<td><input class="mediumtext" type="text" name="newdate_publication[' . $_SESSION['listtips'][$i]['ID_ADVICE'] . ']" value="' . $_SESSION['listtips'][$i]['date_publication'] . '"></td>'; 
                                        
                                        echo '<td><button class="button" type="submit" name="updatetip" value="' . $_SESSION['listtips'][$i]['ID_ADVICE'] . '">Update</button></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </form>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pageTips" value="moins">Précedent</button>
                    <button type="submit" name="pageTips" value="plus">Suivant</button>
                    <button type="submit" name="pageTips">Rafraîchir</button>
                </form>

                <h2>Ajouter une ligne</h2>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">Titre</th>
                        <th scope="col">Thème</th>
                        <th scope="col">Description</th>
                        <th scope="col">Date de publication</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="POST" action="traitement_add.php">
                            <tr>
                                <td><input class="mediumtext" type="text" name="title"></td>

                                <td><input class="mediumtext" type="text" name="theme"></td>

                                <td><input class="bigtext" type="text" name="description"></td>

                                <td><input class="mediumtext" type="text" name="date_publication"></td>
                                        
                                <td><button class="button" type="submit" name="addtip">Ajouter</button></td>
                            </tr>
                        </form>
                    </tbody>
                </table>
                <?php endif; ?>
            </section>

            <section id="pagenotifs" class="mt-5">
                <?php if(isset($_SESSION['listnotifs'])): ?>
                <h1>Liste des notifications</h1>

                <div>
                    <form method="POST" action="traitement_search.php">
                        <input type="text" class="" name="searchvalue" placeholder="Rechercher un élément...">
                        <input type="hidden" name="action" value="notifs">
                    </form>
                </div>
                <br>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Description</th>
                        <th scope="col">Type</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="POST" action="traitement_edit.php">
                            <?php
                                for($i=0; $i<count($_SESSION['listnotifs']); $i++){
                                    echo '<tr>';
                                        echo '<th scope="row">' . htmlspecialchars($_SESSION['listnotifs'][$i]['ID_NOTIFICATION']) . '</th>';
                                        
                                        echo '<td><input class="mediumtext" type="text" name="newtitle[' . $_SESSION['listnotifs'][$i]['ID_NOTIFICATION'] . ']" value="' . $_SESSION['listnotifs'][$i]['title'] . '"></td>'; 
                                        
                                        echo '<td><input class="bigtext" type="text" name="newdescription[' . $_SESSION['listnotifs'][$i]['ID_NOTIFICATION'] . ']" value="' . $_SESSION['listnotifs'][$i]['description'] . '"></td>'; 

                                        echo '<td><input class="mediumtext" type="text" name="newtype[' . $_SESSION['listnotifs'][$i]['ID_NOTIFICATION'] . ']" value="' . $_SESSION['listnotifs'][$i]['type'] . '"></td>'; 

                                        echo '<td><button class="button" type="submit" name="updatenotif" value="' . $_SESSION['listnotifs'][$i]['ID_NOTIFICATION'] . '">Update</button></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </form>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pageNotifs" value="moins">Précedent</button>
                    <button type="submit" name="pageNotifs" value="plus">Suivant</button>
                    <button type="submit" name="pageNotifs">Rafraîchir</button>
                </form>
                <?php endif; ?>
            </section>
        </div>
    </main> 
</body>
