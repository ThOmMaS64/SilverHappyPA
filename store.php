<?php 
    session_start();
    include('traitementsPHP/deconnexionAuto.php'); ?>
<!DOCTYPE html>
<html lang="en">

    <?php 

        include("includes/translation.php");

        $pageTitle = trad("Boutique");

        include("includes/head.php");
        include("includes/header.php");

        if(isset($_SESSION['id'])){

            $dataJson = file_get_contents("http://localhost:8081/showDefaultStorePage?id=". $_SESSION['id']);
            
            $distinctTypes = [];
            $storeList = [];

            if($dataJson){

                $response = json_decode($dataJson, true);

                if(isset($response['error']) && $response['error'] != ""){

                    $errorMessage = $response['error'];

                }else{

                    $distinctTypes = $response['types'];
                    $storeList = $response['products'];

                }

            }
        }else{
            $dataJson = file_get_contents("http://localhost:8081/showDefaultStorePage");
            

            $distinctTypes = [];
            $storeList = [];

            if($dataJson){

                $response = json_decode($dataJson, true);

                if(isset($response['error']) && $response['error'] != ""){

                    $errorMessage = $response['error'];

                }else{

                    $distinctTypes = $response['types'];
                    $storeList = $response['products'];

                }

            }
        }

        if(isset($_GET['research']) || isset($_GET['filter']) || isset($_GET['sort'])){

            if(isset($_GET['research'])){
                $research = urlencode($_GET['research']);
            }else{
                $research = "";
            }

            if(isset($_GET['filter'])){
                $filter = urlencode($_GET['filter']);
            }else{
                $filter = "";
            }

            if(isset($_GET['sort'])){
                $sort = urlencode($_GET['sort']);
            }else{
                $sort = "";
            }

            $id = isset($_SESSION['id']) ? $_SESSION['id'] : "";

            $dataJson = file_get_contents("http://localhost:8081/showPersonalizedStorePage?id=".$id."&research=".$research."&filter=".$filter."&sort=".$sort);

            if($dataJson){

                $response = json_decode($dataJson, true);

                if(isset($response['error']) && $response['error'] != ""){

                    $errorMessage = $response['error'];

                }else{

                    $storeList = $response['products'];

                }

            }
        }

        $notif = [

            "add_success" => "Produit.s correctement ajouté au panier.",

        ];

        $notifKey = $_GET["notif"] ?? null;

        $successMessage = $notif[$notifKey] ?? null;

        $error = [

            "add_error" => "Erreur lors de l'ajout au panier.",

        ];

        $errorKey = $_GET["error"] ?? null;

        $errorMessage = $error[$errorKey] ?? null;

    ?>

    <body>
        <main>
            <div class="backgroundPlain" style="background-color:#f2f6fa; align-items:flex-start;">
                <div class="col-3 ps-4 pe-4" style="color:white; background-color:rgb(62, 134, 189); min-height: 120vh; padding-top:150px; clip-path: polygon(0% 0%, 100% 0%, 100% 90%, 0% 100%);<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                    <h3><?php echo trad("Boutique") ?></h3>
                    <div class="line"></div>
                    <p><?php echo trad("Cette page vous donne accès aux produits partagés par l'équipe Silver Happy et ses nombreux prestataires.") ?></p>

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

                    <form method="GET" action="">
                        <p><strong><?php echo trad("Rechercher un produit :") ?></strong></p>
                        <div class="row mb-5">
                            <div class="input-group">
                                <input value="<?php if(isset($_GET['research'])){ echo htmlspecialchars($_GET['research']); }else{ echo ""; } ?>" class="form-control" name="research" placeholder="<?php if(isset($_GET['research']) && $_GET['research'] != ""){ echo $_GET['research']; }else{ ?><?php echo trad("Tapez votre recherche") ?> <?php } ?>" aria-label="Search">
                                <button class="searchButton" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <p><strong><?php echo trad("Filtrer les produits :") ?></strong></p>
                        <div class="row mb-5">
                            <div class="input-group">
                                <select name="filter" class="selectFilter" onchange="this.form.submit()">
                                    <option disabled selected><?php echo trad("Choisissez un type") ?></option>
                                    <?php foreach($distinctTypes as $type): ?>
                                        <option value="<?= htmlspecialchars($type) ?>" <?php if(isset($_GET['filter']) && $_GET['filter'] == $type){ echo 'selected'; } ?> ><?= htmlspecialchars(trad($type)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <p><strong><?php echo trad("Trier les produits :") ?></strong></p>
                        <div class="row">
                            <div class="input-group">
                                <select name="sort" class="selectFilter" onchange="this.form.submit()">
                                    <option value="" disabled selected><?php echo trad("Choisissez une méthode de tri") ?></option>
                                        <option value="1" <?php if(isset($_GET['sort']) && $_GET['sort'] == "1"){ echo 'selected'; } ?>><?php echo trad("Prix croissant") ?></option>
                                        <option value="2" <?php if(isset($_GET['sort']) && $_GET['sort'] == "2"){ echo 'selected'; } ?>><?php echo trad("Prix décroissant") ?></option>
                                        <option value="3" <?php if(isset($_GET['sort']) && $_GET['sort'] == "3"){ echo 'selected'; } ?>><?php echo trad("Du plus récent au plus ancien") ?></option>
                                        <option value="4" <?php if(isset($_GET['sort']) && $_GET['sort'] == "4"){ echo 'selected'; } ?>><?php echo trad("Du plus ancien au plus récent") ?></option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="lateralAffichage col-9" style="background-color:#f2f6fa;padding-top:140px; padding-bottom:90px;<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>;">
                    <?php if(!empty($storeList)){ ?>
                        <div class="showStore" style="<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4" style="padding:40px;">
                                <?php foreach($storeList as $product){ ?>
                                    <div class="col">
                                        <div class="card h-100">
                                            <div class="card-body d-flex flex-column">
                                                <div class="text-center mb-3">
                                                    <?php if(!empty($product['image_path'])){ ?>
                                                        <img src="data/products_images/<?= $product['image_path'] ?>" alt="image du produit" style="width:100%; height:150px; object-fit:contain;">
                                                    <?php }else{ ?>
                                                        <p><small><i><?php echo trad("Aucune photo partagée") ?></i></small></p>
                                                    <?php } ?>
                                                </div>

                                                <h5><?php echo htmlspecialchars(tradByAPI($product['name'])) ?></h5>
                                                <div class="line"></div>
                                                <p><small class="text-muted">
                                                    <?php echo htmlspecialchars(trad($product['type'])) ?> 
                                                    <?php if($product['stock'] <= 0){ echo trad(" - rupture de stock"); }?>
                                                </small></p>

                                                <p style="overflow:hidden; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; flex-grow:1;">
                                                    <?php echo htmlspecialchars(tradByAPI($product['description'])) ?>
                                                </p>

                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="fw-bold"><?php echo ($product['price']) ?>€</span>
                                                        
                                                        <form id="addToCart_<?= $product['id_product'] ?>" method="POST" action="http://localhost:8081/addToCart?id=<?php if(isset($_SESSION['id'])){ echo $_SESSION['id']; } ?>">
                                                            <input type="hidden" name="id_product" value="<?php echo htmlspecialchars($product['id_product']); ?>">
                                                            <?php if($product['stock'] > 0){ ?>
                                                                <button type="<?php echo isset($_SESSION['id']) ? 'submit' : 'button'; ?>" class="saveUnsaveButton" <?php if(!isset($_SESSION['id'])) { ?> onclick="window.location.href='connexion.php?notif=need_connexion'" <?php } ?>>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-plus" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-1.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5"/>
                                                                        <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
                                                                    </svg>
                                                                </button>
                                                            <?php } ?>
                                                        </form>
                                                    </div>

                                                    <div class="input-group input-group-sm mb-2">
                                                        <span class="input-group-text"><small>Qté</small></span>
                                                        <input form="addToCart_<?= $product['id_product'] ?>" type="number" name="quantity" min="1" max="<?php echo htmlspecialchars($product['stock']); ?>" value="<?php echo (!empty($product['quantity_in_cart'])) ? $product['quantity_in_cart'] : "1"; ?>" class="form-control">
                                                    </div>

                                                    <p class="mb-0"><small class="text-muted"><?php echo trad("Publié le") ?> <?php echo date("d/m/Y", strtotime($product['date_added'])) ?></small></p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                        </div>
                    </div>
                    <?php }else{ ?>

                        <p style="justify-self:center;padding-top:150px;"><?php echo trad("Aucun produit n'a été partagé pour le moment.") ?></p>

                    <?php } ?>
                </div>
            </div>
        </main>
        <?php include("includes/footer.php");
        include('includes/magnifyingLink.php');
        include('includes/audioLink.php'); ?>

        <audio id="audio" src="audios/store.m4a"></audio>

        <script>

            document.getElementById('audioButton').addEventListener('click', function(e){
                e.preventDefault();
                document.getElementById('audio').play();

            })

        </script>
    </body>
</html>