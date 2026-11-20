<?php
// URL: index.php?controller=fruits_delete&id=1
// $_GET['id'] is de id uit de querystring.
if (empty($_SESSION['account'])) {
    echo '<p class="message">Log eerst in.</p>';
    return;
}

$databaseConfig = require 'app/config/database.php';
$getId = (isset($_GET['id']) && is_string($_GET['id'])) ? $_GET['id'] : '';
$message = '';
$id = null;

if (empty($_GET['id'])) {
    $message = 'Geen id. Kies Delete in de lijst, bijvoorbeeld ?controller=fruits_delete&id=1.';
} 
elseif (!is_numeric($getId)) {
    $message = 'id moet een getal zijn.';
} 
else {
    $id = (int) $getId;
    $sql = 'DELETE FROM `fruits` WHERE `id` = ' . $id;
    $connection = mysqli_connect(
        $databaseConfig['host'],
        $databaseConfig['username'],
        $databaseConfig['password'],
        $databaseConfig['database'],
        $databaseConfig['port']
    );

    if (mysqli_query($connection, $sql)) {
        if (mysqli_affected_rows($connection) === 0) {
            $message = 'Geen record met id ' . $id . '.';
        } else {
            $message = 'Record ' . $id . ' is verwijderd.';
        }
    } else {
        $message = 'Verwijderen is niet gelukt.';
    }
}

$messageHtml = '';
if ($message !== '') {
    $messageHtml = '<p class="message">' . escape($message) . '</p>';
}

?>
<h2>Fruit verwijderen</h2>
<p><a class="back-button" href="?controller=fruits_index">Back</a></p>
<?= $messageHtml ?>
