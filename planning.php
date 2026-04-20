<?php 
    session_start();
    include('traitementsPHP/deconnexionAuto.php'); ?>
<!DOCTYPE html>
<html lang="en">
    <?php

    include("includes/translation.php");

    $pageTitle = trad("Planning");

    include("includes/head.php");
    include("includes/header.php");

    if(isset($_SESSION['id'])){

        $slots = [];
        $quotes = [];

        $dataJson = file_get_contents("http://localhost:8081/showServiceProviderSlotsPlanning?id=".$_SESSION['id']);

        if($dataJson){

            $response = json_decode($dataJson, true);

            if(isset($response['error']) && $response['error'] != ""){

                $errorMessage = $response['error'];

            }else{

                $slots = $response['slots'];

            }

        }

        $dataJson = file_get_contents("http://localhost:8081/showServiceProviderQuotesPlanning?id=".$_SESSION['id']);

        if($dataJson){

            $response = json_decode($dataJson, true);

            if(isset($response['error']) && $response['error'] != ""){

                $errorMessage = $response['error'];

            }else{

                $quotes = $response['quotes'];

            }

        }
    }else{

        header('location:connexion.php?need_connexion');
        return;

    }
 
    $providedServices = [];

    if(!empty($slots)){

        foreach($slots as $slot){

            $address = $slot['is_at_consumer_home'] ? trad("Au domicile du client") : $slot['nb_street'] . " " . $slot['street'] . ", " . $slot['city'] . ", " . $slot['postal_code'];

            $providedServices[] = [

                'title' => $slot['service_type'],
                'start' => $slot['start_time'],
                'end' => $slot['end_time'],
                'location' => $address,

            ];

        }

    }

    ?>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    </head>
    <body>
        <main>
            <div class="backgroundPlainCal" style="background-color:#f2f6fa; align-items:flex-start;">
                <div class="col-3 ps-4 pe-4" style="color:white; background-color:rgb(62, 134, 189); min-height: 120vh; padding-top:150px; clip-path: polygon(0% 0%, 100% 0%, 100% 90%, 0% 100%);<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#2A1F1B;<?php endif;endif; ?>;">
                    <div class="row p-3">
                        <h3><?php echo trad("Planning") ?></h3>
                        <div class="line ms-3 mb-1"></div>
                        <p><?php echo trad("Voici votre planning basé sur les prestations que vous proposez sur Silver Happy.") ?></p>
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
                </div>
                <div class="lateralAffichage col-9" style="background-color:#f2f6fa;padding-top:140px; padding-bottom:90px;<?php if(isset($_SESSION['id'])):if($_SESSION['darkMode'] == 1):?>background-color:#1A1412;color:white;<?php endif;endif; ?>;">

  
                    <div id="calendar" class="mt-5"></div>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var calendar = new FullCalendar.Calendar(
                        document.getElementById('calendar'),
                        {
                            initialView: 'dayGridMonth',
                            locale: 'fr',
                            firstDay: 1,

                            headerToolbar:{
                                left:'prev,next,today',
                                center:'title',
                                right:'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                            },

                            buttonText:{
                                today: 'aujourd\'hui',
                                month: 'mois',
                                week: 'semaine',
                                day: 'jour',
                                list: 'liste'
                            },

                            allDayText: 'Toute la journée',
                            noEventsText: 'Aucun événement à afficher',

                            eventClick: function(info) {
                                document.getElementById('modalTitle').textContent = info.event.title;
    
                                let startStr = info.event.start ? info.event.start.toLocaleString('fr-FR') : 'Non défini';
                                let endStr = info.event.end ? info.event.end.toLocaleString('fr-FR') : 'Non défini';
                                
                                document.getElementById('modalStart').textContent = startStr;
                                document.getElementById('modalEnd').textContent = endStr;

                                document.getElementById('modalLocation').textContent = info.event.extendedProps.location;                                

                                new bootstrap.Modal(document.getElementById('eventModal')).show();
                            },

                            events: <?php echo json_encode($providedServices); ?>
                        }
                        );
                        calendar.render();
                    });
                    </script>

                    <div class="row">
                        <h5 class="mt-5">Prestations sur devis</h5>
                        <div class="line mb-4 ms-3"></div>

                        <?php if(!empty($quotes)){ ?>
                            <?php foreach($quotes as $quote){ ?>

                                <p><strong><?= htmlspecialchars($quote['prestation']) ?></strong></p>
                                                        
                                <p>
                                <strong><?= trad("Date / Période :") ?></strong>
                                <?php 
                                    if(!empty($quote['date_start_or_unique']) && empty($quote['date_end'])) {

                                        echo trad("Le ") . date('d/m/Y', strtotime($quote['date_start_or_unique']));

                                    } elseif(!empty($quote['date_start_or_unique']) && !empty($quote['date_end'])) {

                                        echo trad("Du ") . date('d/m/Y', strtotime($quote['date_start_or_unique'])) . trad(" au ") . date('d/m/Y', strtotime($quote['date_end']));

                                    } else {
                                        
                                        echo htmlspecialchars($quote['date_personalized']);

                                    }
                                ?>
                                </p>

                                <?php if(!empty($quote['content'])): ?>
                                    <p><strong><?= trad("Détails de l'accord :") ?></strong><br><br> <?= htmlspecialchars($quote['content']) ?></p>
                                <?php endif; ?>
                                                        
                                <p class="mb-3"><strong><?= trad("Montant convenu :") ?></strong> <?= htmlspecialchars($quote['amount']) ?> €</p>

                                <div class="line2 mt-2 mb-2"></div>
                                <?php } ?>

                        <?php }else{ ?>

                            <p><?= trad("Vous n'avez aucune prestation de réservée pour le moment.") ?></p>

                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="modal" id="eventModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= trad("Détails de l'événement") ?></h5>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Activité : </strong><span id="modalTitle"></span></p>
                        <p><strong>Début : </strong><span id="modalStart"></span></p>
                        <p><strong>Fin : </strong><span id="modalEnd"></span></p>
                        <p><strong>Lieu : </strong><span id="modalLocation"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= trad("Fermer") ?></button>
                    </div>
                    </div>
                </div>
            </div>

        </main>
        <?php include("includes/footer.php");
        include('includes/magnifyingLink.php');
        include('includes/audioLink.php'); ?>

        <audio id="audio" src="audios/planning.m4a"></audio>

        <script>

            document.getElementById('audioButton').addEventListener('click', function(e){
                e.preventDefault();
                document.getElementById('audio').play();

            })

        </script>
    </body>
</html>