<?php
// include_once laadt de functiedefinitie één keer, vóór de HTML.
include_once('app/lib/trigger.php');
$html = triggerController();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>A2.3cd — Pagina’s via de URL</title>
    <link rel="icon" href="images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="title-row">
            <img class="logo" src="images/logo/logo.png" alt="">
            <h1>A2.3cd — Pagina’s via de URL</h1>
        </div>
        <nav aria-label="Controller-menu">
            <a href="?controller=home">Home</a>
            <a href="?controller=about">Over de opleiding</a>
            <a href="?controller=contact">Contact</a>
        </nav>
        <p>Een querystring staat na het vraagteken in de URL. PHP ontvangt controller=home als $_GET['controller'].</p>
    </header>
    <main>
        <hr>
        <article>
            <?= $html ?>
        </article>
    </main>
    <footer class="foot"><p>Schooldemo — bekijk het verschil tussen de URL en de bestandslocatie.</p></footer>
</body>
</html>
