<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="icon" href="medias/logos/logoDessin.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="cssStyles/style.css?v=56">

    <?php require_once "db.php"; ?>

    <?php if(isset($_SESSION['levelFont'])){ ?>

        <?php if($_SESSION['levelFont'] == 1){ ?>

            <link rel="stylesheet" href="cssStyles/styleFontSize1.css?v=0">

        <?php }elseif($_SESSION['levelFont'] == 2){ ?>

            <link rel="stylesheet" href="cssStyles/styleFontSize2.css?v=0">

        <?php }elseif($_SESSION['levelFont'] == 3){ ?>

            <link rel="stylesheet" href="cssStyles/styleFontSize3.css?v=1">

        <?php } ?>

    <?php } ?>

    <?php if(isset($_SESSION['fontChange']) && $_SESSION['fontChange'] == 1){ ?>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:ital,wght@0,400;0,700;1,400;1,700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="cssStyles/styleFontChange.css?v=0">

    <?php } ?>

    <?php if(isset($_SESSION['cursorType']) && $_SESSION['cursorType'] == 1){ ?>

        <link rel="stylesheet" href="cssStyles/styleBigCursor.css?v=2">

    <?php } ?>
    
</head>