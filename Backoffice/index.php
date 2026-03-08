<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office Silver Happy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<?php
session_start();
$dataConn = file_get_contents("http://localhost:8081/backShowConn");
$dataUser = file_get_contents("http://localhost:8081/backShowUsers");
$dataShop = file_get_contents("http://localhost:8081/backShowShop");
$dataEvent = file_get_contents("http://localhost:8081/backShowEvents");
$dataTip = file_get_contents("http://localhost:8081/backShowTips");

if($dataConn){

    $usersC = json_decode($dataConn, true);
    $_SESSION['listconn'] = array();

    foreach($usersC as $user){
        $userConn = array([
            'ID_USER' => $user['ID_USER'],
            'username' => $user['username'],
            'email' => $user['email'],
            'connected' => $user['connected']
        ]);
        $_SESSION['listconn'][] = $userConn;
    }
}

if($dataUser){

    $users = json_decode($dataUser, true);
    $_SESSION['listusers'] = array();

    foreach($users as $user){
        $registeredUser = array([
            'ID_USER' => $user['ID_USER'],
            'username' => $user['username'],
            'name' => $user['name'],
            'surname' => $user['surname'],
            'description' => $user['description'],
            'email' => $user['email'],
            'status' => $user['status']
        ]);
        $_SESSION['listusers'][] = $registeredUser;
    }
}

if($dataShop){

    $shop = json_decode($dataShop, true);
    $_SESSION['listshop'] = array();

    foreach($shop as $product){
        $registeredProduct = array([
            'ID_PRODUCT' => $product['ID_PRODUCT'],
            'name' => $product['name'],
            'type' => $product['type'],
            'description' => $product['description'],
            'price' => $product['price']
        ]);
        $_SESSION['listshop'][] = $registeredProduct;
    }
}

if($dataEvent){

    $events = json_decode($dataEvent, true);
    $_SESSION['listevents'] = array();

    foreach($events as $event){
        $registeredEvents = array([
            'ID_EVENT' => $event['ID_EVENT'],
            'name' => $event['name'],
            'type' => $event['type'],
            'date_' => $event['date_'],
            'description' => $event['description']
        ]);
        $_SESSION['listevents'][] = $registeredEvents;
    }
}

if($dataTip){

    $tips = json_decode($dataTip, true);
    $_SESSION['listtips'] = array();

    foreach($tips as $tip){
        $registeredTips = array([
            'ID_ADVICE' => $tip['ID_ADVICE'],
            'title' => $tip['title'],
            'theme' => $tip['theme'],
            'description' => $tip['description'],
            'date_publication' => $tip['date_publication']
        ]);
        $_SESSION['listtips'][] = $registeredTips;
    }
}
?>
<body onload="loadWelcome()">
    <header>
        <img src="image/logo1.png" alt="logo Silver Happy">
    </header>
    <main>
        <div class="nav">
            <ul>
                <li><a href="#pagewelcome" onclick="showWelcome()">Accueil</a></li>

                <form id="connForm" method="POST" action="traitements.php">
                    <input type="hidden" name="action" value="conn">
                </form>
                <li><a href="#pageconnexion" onclick="hideWelcome(); document.getElementById('connForm').submit(); return false;" action="traitements.php">Utilisateurs connectés</a></li>

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
            <?php if(isset($_SESSION['flash_message']) && $_SESSION['flash_message']['type'] === 'success'): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo '<p>' . htmlspecialchars($_SESSION['flash_message']['message']) . '</p>'; ?>
                </div>
                <?php unset($_SESSION['flash_message']); ?>
            <?php endif; ?>
            <div id="pagewelcome">
                <h1>Back Office Silver Happy</h1>
                <p>Utilisez la navigation à gauche pour accéder aux outils d'administration</p>
                </div>

            <section id="pageconnexion">
                <?php if(isset($_SESSION['listconn'])): ?>

                <h1>Utilisateurs connectés : <?php echo $_SESSION['total']; ?></h1>

                <div>
                    <form method="POST" action="traitement_search.php">
                        <input type="text" class="" name="searchvalue" placeholder="Rechercher un élément...">
                        <input type="hidden" name="action" value="conn">
                    </form>
                </div>
                <br>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Email</th>
                        <th scope="col">Connecté ?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for($i=0; $i<count($_SESSION['listconn']); $i++){
                                echo '<tr>';
                                    echo '<th scope="row">' . htmlspecialchars($_SESSION['listconn'][$i][0]['ID_USER']) . '</th>';
                                    echo '<td>' . htmlspecialchars($_SESSION['listconn'][$i][0]['username']) . '</td>';
                                    echo '<td>' . htmlspecialchars($_SESSION['listconn'][$i][0]['email']) . '</td>';
                                    if($_SESSION['listconn'][$i][0]['connected']==0){
                                        echo '<td>Non</td>';
                                    }else{
                                        echo '<td>Oui</td>';
                                    }
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pageConn" value="moins">Précedent</button>
                    <button type="submit" name="pageConn" value="plus">Suivant</button>
                    <button type="submit" name="pageConn">Rafraîchir</button>
                </form>
                <?php endif; ?>
            </section>

            <section id="pageusers">
                <?php if(isset($_SESSION['listusers'])): ?>
                <h1>Liste des utilisateurs</h1>

                <div>
                    <form method="POST" action="traitement_search.php">
                        <input type="text" class="" name="searchvalue" placeholder="Rechercher un élément...">
                        <input type="hidden" name="action" value="users">
                    </form>
                </div>
                <br>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom d'utilisateur</th>
                        <th scope="col">Date de naissance</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Description</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="POST" action="traitement_edit.php">
                            <?php
                                for($i=0; $i<count($_SESSION['listusers']); $i++){
                                    echo '<tr>';
                                        echo '<th scope="row">' . htmlspecialchars($_SESSION['listusers'][$i][0]['ID_USER']) . '</th>';

                                        echo '<td><input class="mediumtext" type="text" name="newusername[' . $_SESSION['listusers'][$i][0]['ID_USER'] . ']" value="' . $_SESSION['listusers'][$i][0]['username'] . '"></td>'; 
                                        
                                        echo '<td></td>';
                                        
                                        echo '<td><input class="mediumtext" type="text" name="newname[' . $_SESSION['listusers'][$i][0]['ID_USER'] . ']" value="' . $_SESSION['listusers'][$i][0]['name'] . '"></td>';

                                        echo '<td><input class="mediumtext" type="text" name="newsurname[' . $_SESSION['listusers'][$i][0]['ID_USER'] . ']" value="' . $_SESSION['listusers'][$i][0]['surname'] . '"></td>';

                                        if(!empty($_SESSION['listusers'][$i][0]['description'])){
                                            echo '<td><input class="bigtext" type="text" name="newdescription[' . $_SESSION['listusers'][$i][0]['ID_USER'] . ']" value="' . $_SESSION['listusers'][$i][0]['description'] . '"></td>';
                                        } else {
                                            echo '<td><input class="bigtext" type="text" name="newdescription[' . $_SESSION['listusers'][$i][0]['ID_USER'] . ']"></td>';
                                        }

                                        echo '<td>' . htmlspecialchars($_SESSION['listusers'][$i][0]['email']) . '</td>';
                                        echo '<td>' . htmlspecialchars($_SESSION['listusers'][$i][0]['status']) . '</td>';

                                        echo '<td><button type="submit" name="updateuser" value="' . $_SESSION['listusers'][$i][0]['ID_USER'] . '">Update</button></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </form>
                    </tbody>
                </table>

                <form method="POST" action="traitement_offset.php">
                    <button type="submit" name="pageUsers" value="moins">Précedent</button>
                    <button type="submit" name="pageUsers" value="plus">Suivant</button>
                    <button type="submit" name="pageUsers">Rafraîchir</button>
                </form>
                <?php endif; ?>
            </section>

            <section id="pageshop">
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
                                        echo '<th scope="row">' . htmlspecialchars($_SESSION['listshop'][$i][0]['ID_PRODUCT']) . '</th>';

                                        echo '<td><input class="bigtext" type="text" name="newname[' . $_SESSION['listshop'][$i][0]['ID_PRODUCT'] . ']" value="' . $_SESSION['listshop'][$i][0]['name'] . '"></td>'; 
                                        
                                        echo '<td><input class="mediumtext" type="text" name="newtype[' . $_SESSION['listshop'][$i][0]['ID_PRODUCT'] . ']" value="' . $_SESSION['listshop'][$i][0]['type'] . '"></td>'; 

                                        echo '<td><input class="bigtext" type="text" name="newdescription[' . $_SESSION['listshop'][$i][0]['ID_PRODUCT'] . ']" value="' . $_SESSION['listshop'][$i][0]['description'] . '"></td>';

                                        echo '<td><input class="smalltext" type="text" name="newprice[' . $_SESSION['listshop'][$i][0]['ID_PRODUCT'] . ']" value="' . $_SESSION['listshop'][$i][0]['price'] . '"></td>'; 

                                        echo '<td><button class="button" type="submit" name="updateshop" value="' . $_SESSION['listshop'][$i][0]['ID_PRODUCT'] . '">Update</button></td>';
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

            <section id="pageevents">
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
                                        echo '<th scope="row">' . htmlspecialchars($_SESSION['listevents'][$i][0]['ID_EVENT']) . '</th>';

                                        echo '<td><input class="mediumtext" type="text" name="newname[' . $_SESSION['listevents'][$i][0]['ID_EVENT'] . ']" value="' . $_SESSION['listevents'][$i][0]['name'] . '"></td>'; 

                                        echo '<td><input class="mediumtext" type="text" name="newtype[' . $_SESSION['listevents'][$i][0]['ID_EVENT'] . ']" value="' . $_SESSION['listevents'][$i][0]['type'] . '"></td>';
                                        
                                        echo '<td><input class="mediumtext" type="text" name="newdate_[' . $_SESSION['listevents'][$i][0]['ID_EVENT'] . ']" value="' . $_SESSION['listevents'][$i][0]['date_'] . '"></td>'; 

                                        echo '<td><input class="bigtext" type="text" name="newdescription[' . $_SESSION['listevents'][$i][0]['ID_EVENT'] . ']" value="' . $_SESSION['listevents'][$i][0]['description'] . '"></td>'; 
                                        
                                        echo '<td><button class="button" type="submit" name="updateevent" value="' . $_SESSION['listevents'][$i][0]['ID_EVENT'] . '">Update</button></td>';
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

            <section id="pagemoney">
                
            </section>
            
            <section id="pagetips">
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
                                        echo '<th scope="row">' . htmlspecialchars($_SESSION['listtips'][$i][0]['ID_ADVICE']) . '</th>';

                                        echo '<td><input class="mediumtext" type="text" name="newtitle[' . $_SESSION['listtips'][$i][0]['ID_ADVICE'] . ']" value="' . $_SESSION['listtips'][$i][0]['title'] . '"></td>'; 

                                        echo '<td><input class="mediumtext" type="text" name="newtheme[' . $_SESSION['listtips'][$i][0]['ID_ADVICE'] . ']" value="' . $_SESSION['listtips'][$i][0]['theme'] . '"></td>'; 

                                        echo '<td><input class="bigtext" type="text" name="newdescription[' . $_SESSION['listtips'][$i][0]['ID_ADVICE'] . ']" value="' . $_SESSION['listtips'][$i][0]['description'] . '"></td>'; 

                                        echo '<td><input class="mediumtext" type="text" name="newdate_publication[' . $_SESSION['listtips'][$i][0]['ID_ADVICE'] . ']" value="' . $_SESSION['listtips'][$i][0]['date_publication'] . '"></td>'; 
                                        
                                        echo '<td><button class="button" type="submit" name="updatetip" value="' . $_SESSION['listtips'][$i][0]['ID_ADVICE'] . '">Update</button></td>';
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

            <section id="pagenotifs">
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
                                        echo '<th scope="row">' . htmlspecialchars($_SESSION['listnotifs'][$i][0]['ID_NOTIFICATION']) . '</th>';
                                        
                                        echo '<td><input class="mediumtext" type="text" name="newtitle[' . $_SESSION['listnotifs'][$i][0]['ID_NOTIFICATION'] . ']" value="' . $_SESSION['listnotifs'][$i][0]['title'] . '"></td>'; 
                                        
                                        echo '<td><input class="bigtext" type="text" name="newdescription[' . $_SESSION['listnotifs'][$i][0]['ID_NOTIFICATION'] . ']" value="' . $_SESSION['listnotifs'][$i][0]['description'] . '"></td>'; 

                                        echo '<td><input class="mediumtext" type="text" name="newtype[' . $_SESSION['listnotifs'][$i][0]['ID_NOTIFICATION'] . ']" value="' . $_SESSION['listnotifs'][$i][0]['type'] . '"></td>'; 

                                        echo '<td><button class="button" type="submit" name="updatenotif" value="' . $_SESSION['listnotifs'][$i][0]['ID_NOTIFICATION'] . '">Update</button></td>';
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
