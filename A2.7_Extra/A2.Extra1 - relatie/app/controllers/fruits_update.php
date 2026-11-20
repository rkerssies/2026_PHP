<?php
// URL: index.php?controller=fruits_update&id=1
// Zonder POST zie je het formulier. Met POST wordt de UPDATE uitgevoerd.
if (!isLoggedIn()) {
    echo '<p class="message">Log eerst in.</p>';
    return;
}

$databaseConfig = require 'app/config/database.php';
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

if (empty($_GET['id'])) {
    $message = 'Geen id. Kies Update in de lijst, bijvoorbeeld ?controller=fruits_update&id=1.';
    $showPage = false;
} elseif (!is_numeric($getId)) {
    $message = 'id moet een getal zijn.';
    $showPage = false;
} else {
    $id = (int) $getId;
    $connection = mysqli_connect(
        $databaseConfig['host'],
        $databaseConfig['username'],
        $databaseConfig['password'],
        $databaseConfig['database'],
        $databaseConfig['port']
    );

    if (empty($_POST)) {
        $selectSql = 'SELECT * FROM `fruits` WHERE `id` = ' . $id;
        $result = mysqli_query($connection, $selectSql);
        $fruit = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

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
        $nameSql = mysqli_real_escape_string($connection, $name);
        $colorSql = mysqli_real_escape_string($connection, $color);
        $seasonSql = mysqli_real_escape_string($connection, $season);
        $sql = "UPDATE `fruits` 
                    SET `name` = '" . $nameSql . "', 
                        `color` = '" . $colorSql . "', 
                        `sweetness` = " . $sweetnessInt . ", 
                        `season` = '" . $seasonSql . "' 
                        WHERE `id` = " . $id;

        if (mysqli_query($connection, $sql)) {
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
