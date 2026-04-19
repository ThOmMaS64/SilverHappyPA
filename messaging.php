<?php 
    session_start();
    include('traitementsPHP/deconnexionAuto.php'); ?>
<!DOCTYPE html>
<html lang="en">
    <?php

    include("includes/translation.php");

    $pageTitle = trad("Communications");

    include("includes/head.php");
    include("includes/header.php");

    if(isset($_SESSION['id'])){
        $dataJson = file_get_contents("http://localhost:8081/showDiscussions?id=". $_SESSION['id']);
                
        if($dataJson){

            $discussionList = json_decode($dataJson, true);

        }
    }else{

        header("location:connexion.php?notif=need_connexion");

    }

    if(isset($_GET['id_discussion'])){

        $dataJson2 = file_get_contents("http://localhost:8081/showMessages?id_discussion=". $_GET['id_discussion']);

        if($dataJson2){

            $messagesList = json_decode($dataJson2, true);

        }

    }

    $error = [

        "sending_error" => trad("Échec lors de l'envoi du message, veuillez réessayer."),

    ];

    $errorKey = $_GET["error"] ?? null;

    $errorMessage = $error[$errorKey] ?? null;

    $notif = [

            "quote_sent" => trad("Votre devis à été envoyé avec succès."),

        ];

        $notifKey = $_GET["notif"] ?? null;

        $successMessage = $notif[$notifKey] ?? null;

    ?>
    <body>
        <main>
            <div class="backgroundPlain" style="background-color:#f2f6fa; align-items:flex-start;">
                <div class="col-3 ps-4 pe-4" style="color:white; background-color:rgb(62, 134, 189); min-height: 120vh; padding-top:150px; clip-path: polygon(0% 0%, 100% 0%, 100% 90%, 0% 100%);<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                    <h3><?php echo trad("Discussions") ?></h3>
                    <div class="line"></div>
                    <p><?php echo trad("Cette page vous donne accès à vos discussions avec d'autres membres de Silver Happy.") ?></p>

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

                    <?php if(!empty($discussionList)){ ?>

                        <?php foreach($discussionList as $discussion): ?>

                            <a class="discussionLink" href="messaging.php?id_discussion=<?= htmlspecialchars($discussion['ID_DISCUSSION']) ?>&id_service=<?= htmlspecialchars($discussion['ID_SERVICE']) ?>" style="text-decoration:none;color:white;">
                                    <p class="mb-2">- <strong><?= htmlspecialchars($discussion['correspondent_name']) . " " . htmlspecialchars($discussion['correspondent_surname']) ?></strong></p>
                            </a>

                        <?php endforeach ?>

                    <?php } ?>
                    
                </div> 
                <div class="lateralAffichage col-9" style="background-color:#f2f6fa;padding-top:140px; padding-bottom:90px;<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>;">
                    <?php if(isset($_GET['id_discussion'])){ ?>

                        <div id="zoneMessage" class="zoneMessage mb-3 mt-2 me-4">
                            <?php

                                foreach ($messagesList as $message) {
                                    if($message['id_user'] == $_SESSION['id']){
                                        echo "<div class='myMessage mb-2'>";
                                        echo "<a href='profile.php'><small style='opacity:0.8;display:block;'>" . htmlspecialchars($message['name']) . " " . htmlspecialchars($message['surname']) . "</small></a>" . htmlspecialchars($message['content']);
                                        echo "<br><small style='opacity:0.8;display:block;' class='mt-1'>" . $message['date'] . "</small>";
                                        echo "</div>";
                                    }else{
                                        echo "<div class='othersMessage mb-2'>";
                                        echo "<a href='profileVisit.php?visitedId=" . $message['id_user'] . "'><small style='opacity:0.8;display:block;'>" . htmlspecialchars($message['name']) . " " . htmlspecialchars($message['surname']) . "</small></a>" . htmlspecialchars($message['content']);
                                        echo "<br><small style='opacity:0.8;display:block;' class='mt-1'>" . $message['date'] . "</small>";
                                        echo "</div>";
                                    }
                                }
                            ?>
                        </div>
                        <div>
                            <form method="POST" action="http://localhost:8081/sendMessage" class="row writingZone">
                                
                                <?php if($_SESSION['status'] == 4){ ?>

                                    <input type="hidden" name="id_discussion" value="<?php echo htmlspecialchars($_GET['id_discussion']);?>">
                                    <input type="hidden" name="id_service" value="<?php echo htmlspecialchars($_GET['id_service'] ?? '');?>">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION['id']);?>">
                                    <div class="col-10">
                                        <input type="text" class="form-control" name="content"></input>
                                    </div>
                                    <div class="col-1">
                                        <button class="btn" type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                                <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="col-1">
                                        <a class="btn mt-1 me-5" href="quoteForm.php?id_discussion=<?= $_GET['id_discussion'] ?>&id_service=<?= htmlspecialchars($_GET['id_service'] ?? '') ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-calculator" viewBox="0 0 16 16">
                                                <path d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                                <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                                            </svg>
                                        </a>
                                    </div>

                                <?php }else{ ?>

                                    <input type="hidden" name="id_discussion" value="<?php echo htmlspecialchars($_GET['id_discussion']);?>">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION['id']);?>">
                                    <div class="col-11">
                                        <input type="text" class="form-control" name="content" <?php if(isset($_GET['info']) && $_GET['info'] == "from_services"){ ?> value="Bonjour, je suis intéréssé par la réalisation d'un devis pour l'une de vos prestations." <?php }elseif(isset($_GET['notif']) && $_GET['notif'] == "quote_sent"){ ?> value="Un devis viens de vous être envoyé, retrouvez le sur votre profil et revenez vers moi pour toute demande." <?php } ?>></input>
                                    </div>
                                    <div class="col-1">
                                        <button class="btn" type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                                <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"/>
                                            </svg>
                                        </button>
                                    </div>

                                <?php } ?>

                            </form>
                        </div>

                    <?php }else{ ?>

                        <svg class="d-block mx-auto" style="margin-top:150px;" xmlns="http://www.w3.org/2000/svg" width="86" height="86" fill="currentColor" class="bi bi-wechat" viewBox="0 0 16 16">
                            <path d="M11.176 14.429c-2.665 0-4.826-1.8-4.826-4.018 0-2.22 2.159-4.02 4.824-4.02S16 8.191 16 10.411c0 1.21-.65 2.301-1.666 3.036a.32.32 0 0 0-.12.366l.218.81a.6.6 0 0 1 .029.117.166.166 0 0 1-.162.162.2.2 0 0 1-.092-.03l-1.057-.61a.5.5 0 0 0-.256-.074.5.5 0 0 0-.142.021 5.7 5.7 0 0 1-1.576.22M9.064 9.542a.647.647 0 1 0 .557-1 .645.645 0 0 0-.646.647.6.6 0 0 0 .09.353Zm3.232.001a.646.646 0 1 0 .546-1 .645.645 0 0 0-.644.644.63.63 0 0 0 .098.356"/>
                            <path d="M0 6.826c0 1.455.781 2.765 2.001 3.656a.385.385 0 0 1 .143.439l-.161.6-.1.373a.5.5 0 0 0-.032.14.19.19 0 0 0 .193.193q.06 0 .111-.029l1.268-.733a.6.6 0 0 1 .308-.088q.088 0 .171.025a6.8 6.8 0 0 0 1.625.26 4.5 4.5 0 0 1-.177-1.251c0-2.936 2.785-5.02 5.824-5.02l.15.002C10.587 3.429 8.392 2 5.796 2 2.596 2 0 4.16 0 6.826m4.632-1.555a.77.77 0 1 1-1.54 0 .77.77 0 0 1 1.54 0m3.875 0a.77.77 0 1 1-1.54 0 .77.77 0 0 1 1.54 0"/>
                        </svg>

                        <p style="justify-self:center;padding-top:20px;"><?php echo trad("Séléctionnez une discussion dans le menu latéral.") ?></p>

                    <?php } ?>
                </div>
            </div>
        </main>
        <?php include("includes/footer.php");
        include('includes/magnifyingLink.php');
        include('includes/audioLink.php'); ?>

        <audio id="audio" src="audios/messaging.m4a"></audio>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var chatBox = document.getElementById("zoneMessage");
                if (chatBox) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            });
        </script>

        <script>

            document.getElementById('audioButton').addEventListener('click', function(e){
                e.preventDefault();
                document.getElementById('audio').play();

            })

        </script>
    </body>
</html>