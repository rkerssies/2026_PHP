<?php
// URL: index.php?controller=fruits_update&id=1
// Zonder POST zie je het formulier. Met POST wordt de UPDATE uitgevoerd.
if (empty($_SESSION['account'])) {
    echo '<p class="message">Log eerst in.</p>';
    return;
}

$databaseConfig = CONFIG['database'];

$getId = '';
if (isset($_GET['id'])) {
    $getId = $_GET['id'];
}
$name = '';
if (isset($_POST['name'])) {
    $name = trim($_POST['name']);
}
$color = '';
if (isset($_POST['color'])) {
    $color = trim($_POST['color']);
}
$sweetness = '';
if (isset($_POST['sweetness'])) {
    $sweetness = trim($_POST['sweetness']);
}
$season = '';
if (isset($_POST['season'])) {
    $season = trim($_POST['season']);
}

$message = '';
$showPage = true;
$id = null;
$fruit = null;

if ($getId === '') {
    $message = 'Geen id. Kies Update in de lijst, bijvoorbeeld ?controller=fruits_update&id=1.';
    $showPage = false;
} elseif (!is_numeric($getId)) {
    $message = 'id moet een getal zijn.';
    $showPage = false;
} else {
    $id = (int) $getId;
    $connection = dbConnect();
    // mysqli_set_charset($connection, $databaseConfig['charset']);

    if (empty($_POST)) {
        // SELECT * haalt ALLE kolommen van dit fruit op, zodat het formulier de huidige waarden toont.
        // id komt uit $_GET['id'], bijvoorbeeld ?controller=fruits_update&id=1
        $selectSql = 'SELECT * FROM `fruits` WHERE `id` = ' . $id;

        // De query wordt hier, in de controller, uitgevoerd.
        $result = dbQuery($connection, $selectSql);
        $fruit = (is_array($result) && isset($result[0])) ? $result[0] : null;

        if ($fruit === null) {
            $message = 'Geen record met id ' . $id . '.';
        } else {
            $name = (string) $fruit['name'];
            $color = (string) $fruit['color'];
            $sweetness = (string) $fruit['sweetness'];
            $season = (string) $fruit['season'];
        }
    } elseif ($name === '' || $color === '' || $season === '' || !is_numeric($sweetness)) {
        $message = 'Vul name, color, sweetness en season in. Sweetness is een getal.';
    } else {
        $sweetnessInt = (int) $sweetness;

        // UPDATE wijzigt name, color, sweetness en season van één rij.
        // De nieuwe waarden komen uit $_POST. id komt uit $_GET['id'].
        $sql = "UPDATE `fruits` 
                    SET `name` = '" . $name . "', 
                        `color` = '" . $color . "', 
                        `sweetness` = " . $sweetnessInt . ", 
                        `season` = '" . $season . "' 
                        WHERE `id` = " . $id;

        // De query wordt hier, in de controller, uitgevoerd.
        $result = dbQuery($connection, $sql);

        if ($result) {
            $message = 'Record ' . $id . ' is gewijzigd.';
        } else {
            $message = 'Wijzigen is niet gelukt.';
        }
    }
}

if (!$showPage) {
    ?>
<p class="message"><?= escape($message) ?></p>
    <?php
    return;
}

$messageHtml = $message !== '' ? '<p class="message">' . escape($message) . '</p>' : '';

?>
<h2>Fruit wijzigen</h2>
<p><a class="back-button" href="?controller=fruits_index">Back</a></p>
<?= $messageHtml ?>
<form method="post" action="?controller=fruits_update&id=<?= $id ?>">
    <label for="name">name</label>
    <input id="name" name="name" value="<?= escape($name) ?>">
    <label for="color">color</label>
    <input id="color" name="color" value="<?= escape($color) ?>">
    <label for="sweetness">sweetness</label>
    <input id="sweetness" name="sweetness" value="<?= escape($sweetness) ?>">
    <label for="season">season</label>
    <input id="season" name="season" value="<?= escape($season) ?>">
    <button type="submit">Update</button>
</form>
