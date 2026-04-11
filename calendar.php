<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();

    include("includes/translation.php");

    $pageTitle = trad("Calendrier");

    include("includes/head.php");
    include("includes/header.php");


    if(isset($_SESSION['id'])){
        $dataJson = file_get_contents("http://localhost:8081/showRegisteredEvent?id=".$_SESSION['id']);

        if($dataJson){

            $response = json_decode($dataJson, true);

            if(isset($response['error']) && $response['error'] != ""){

                $errorMessage = $response['error'];

            }else{

                $eventList = $response['events'];

            }

        }
    }
 
    if (!empty($eventList)) {
    foreach ($eventList as $event) {

        $address = $event['nb_street'] . " " . $event['street'] . ", " . $event['city'] . ", " . $event['postal_code'];

        $events[] = [
            'title' => $event['name'],
            'start' => $event['date_start'],
            'end' => $event['date_end'],
            'location' => $address
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
                        <h3><?php echo trad("Calendrier") ?></h3>
                        <div class="line ms-3 mb-1"></div>
                        <p><?php echo trad("Voici votre calendrier basé sur les prestations et événements auxquels vous vous êtes inscrits.") ?></p>
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

                            events: <?php echo json_encode($events); ?>
                        }
                        );
                        calendar.render();
                    });
                    </script>
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
        <?php include("includes/footer.php") ?>
    </body>
</html>