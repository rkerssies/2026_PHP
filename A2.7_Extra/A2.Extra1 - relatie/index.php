<?php
session_start();
require_once 'app/lib/helpers/escape.php';
require_once 'app/lib/checklogin.php';
require_once 'app/lib/trigger.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>A2.Extra1 — Relatie</title>
    <link rel="icon" href="images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a class="account-button" href="?controller=logon">
        <svg class="account-icon" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 20c1.6-3.6 4.4-5.2 8-5.2s6.4 1.6 8 5.2"></path></svg>
        <span>&emsp;account</span>
    </a>
    <header>
        <div class="title-row">
            <img class="logo" src="images/logo/logo.png" alt="">
            <h1>A2.Extra1 — Relatie</h1>
        </div>
        <nav aria-label="Controller-menu">
            <a href="?controller=home">Home</a>
            <a href="?controller=fruits_index">Alle fruitsoorten</a>
            <a href="?controller=fruits_aggregate">Aggregates</a>
            <a href="?controller=about">Over de opleiding</a>
            <a href="?controller=contact">Contact</a>
        </nav>
    </header>
    <main>
        <article>
            <?php triggerController(); ?>
        </article>
    </main>
    <footer>
        <p>School demo (c) <?= date('Y') ?></p>
    </footer>
</body>
</html>
