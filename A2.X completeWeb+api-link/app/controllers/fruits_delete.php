<?php
// URL: index.php?controller=fruits_delete&id=1
// $_GET['id'] is de id uit de querystring.
if (empty($_SESSION['account'])) {
    echo '<p class="message">Log eerst in.</p>';
    return;
}

$databaseConfig = CONFIG['database'];

$getId = (isset($_GET['id']) && is_string($_GET['id'])) ? $_GET['id'] : '';
$message = '';
$id = null;

if ($getId === '') {
    $message = 'Geen id. Kies Delete in de lijst, bijvoorbeeld ?controller=fruits_delete&id=1.';
} elseif (!is_numeric($getId)) {
    $message = 'id moet een getal zijn.';
} else {
    $id = (int) $getId;

    // DELETE verwijdert één rij uit fruits.
    // id komt uit $_GET['id'], bijvoorbeeld ?controller=fruits_delete&id=1
    $sql = 'DELETE FROM `fruits` WHERE `id` = ' . $id;

    $connection = dbConnect();
    // mysqli_set_charset($connection, $databaseConfig['charset']);

    // De query wordt hier, in de controller, uitgevoerd.
    $result = dbQuery($connection, $sql);

    if ($result) {
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
