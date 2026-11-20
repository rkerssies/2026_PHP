<?php
session_start();
require_once './../app/lib/helpers/base_path.php';
require_once basePath('app/lib/helpers/escape.php');
require_once basePath('app/lib/read_config.php');
require_once basePath('app/lib/db_mysqli/db_connect.php');
require_once basePath('app/lib/db_mysqli/db_query.php');
require_once basePath('app/lib/trigger.php');
require_once basePath('app/lib/css_links.php');

escapeInput();
$config = readConfig();

// API-controllers geven alleen JSON terug, zonder HTML-layout.
if (isset($_GET['controller']) && $_GET['controller'] === 'fruits_api') {
    triggerController();
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= escape($config['site']['title']) ?></title>
    <meta name="author" content="<?= escape($config['site']['author']) ?>">
    <meta name="description" content="<?= escape($config['seo']['description']) ?>">
    <meta name="keywords" content="<?= escape($config['seo']['keywords']) ?>">
    <meta name="robots" content="<?= escape($config['seo']['robots']) ?>">
    <link rel="icon" href="images/logo/favicon.png" type="image/png">
    <?= cssLinks() ?>
</head>
<body>
    <a class="account-button" href="?controller=logon">
        <svg class="account-icon" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 20c1.6-3.6 4.4-5.2 8-5.2s6.4 1.6 8 5.2"></path></svg>
        <span>&emsp;account</span>
    </a>
    <header>
        <div class="site-brand">
            <img class="site-logo" src="images/logo/logo.png" alt="" width="48" height="48">
            <h1><?= escape($config['site']['name']) ?></h1>
        </div>
        <nav aria-label="Controller-menu">
            <a href="?controller=home">Home</a>
            <a href="?controller=fruits_index">Alle fruitsoorten</a>
            <a href="?controller=fruits_aggregate">Aggregates</a>
            <a href="?controller=fruits_api">Fruit-API</a>
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
        <p><?= escape($config['site']['author']) ?> demo (c) <?= date('Y') ?></p>
    </footer>
</body>
</html>
