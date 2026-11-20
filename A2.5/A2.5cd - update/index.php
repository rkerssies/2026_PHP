<?php
require_once 'app/lib/helpers/escape.php';
require_once 'app/lib/trigger.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>A2.5cd — UPDATE</title>
    <link rel="icon" href="images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="title-row">
            <img class="logo" src="images/logo/logo.png" alt="">
            <h1>A2.5cd — UPDATE</h1>
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
