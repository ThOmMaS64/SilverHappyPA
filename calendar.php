<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();

    include("includes/translation.php");

    $pageTitle = trad("Calendrier");

    include("includes/head.php");
    include("includes/header.php");


    if(isset($_SESSION['id'])){
        $dataJson = file_get_contents("http://localhost:8081/showSavedEvent?id=".$_SESSION['id']);

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
        $events[] = [
            'title' => $event['name'],
            'start'  => $event['date_start'],
            'end'  => $event['date_end']
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
                    <h3><?php echo trad("Calendrier") ?></h3>
                    <div class="line"></div>
                    <p><?php echo trad("Voici votre calendrier. Vous pouvez le modifier en ajoutant des événements ou prestations.") ?></p>

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

  
                    <div id="calendar"></div>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var calendar = new FullCalendar.Calendar(
                        document.getElementById('calendar'),
                        {
                            initialView: 'dayGridMonth',
                            locale: 'fr',
                            firstDay: 1,
                            events: <?php echo json_encode($events); ?>
                        }
                        );
                        calendar.render();
                    });
                    </script>
                </div>
            </div>
        </main>
        <?php include("includes/footer.php") ?>
    </body>
</html>