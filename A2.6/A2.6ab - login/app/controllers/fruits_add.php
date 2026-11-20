<?php
// URL: index.php?controller=fruits_add
// Het formulier post name, color, sweetness en season.
if (empty($_SESSION['account'])) {
    echo '<p class="message">Log eerst in.</p>';
    return;
}

$databaseConfig = require 'app/config/database.php';
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
$newId = null;

if (!empty($_POST)) {
    /// basic validatie
    if ($name === '' || $color === '' || $season === '' || !is_numeric($sweetness)) {
        $message = 'Vul name, color, sweetness en season in. Sweetness is een getal.';
    } 
    else {
        $sweetnessInt = (int) $sweetness;
        $connection = mysqli_connect(
            $databaseConfig['host'],
            $databaseConfig['username'],
            $databaseConfig['password'],
            $databaseConfig['database'],
            $databaseConfig['port']
        );
        $nameSql = mysqli_real_escape_string($connection, $name);
        $colorSql = mysqli_real_escape_string($connection, $color);
        $seasonSql = mysqli_real_escape_string($connection, $season);
        $sql = "INSERT INTO `fruits` (`name`, `color`, `sweetness`, `season`) VALUES ('" . $nameSql . "', '" . $colorSql . "', " . $sweetnessInt . ", '" . $seasonSql . "')";

        if (mysqli_query($connection, $sql)) {
            $newId = mysqli_insert_id($connection);
            $message = 'Toegevoegd met id ' . $newId . '.';
        } else {
            $message = 'Toevoegen is niet gelukt.';
        }
    }
}

$messageHtml = $message !== '' ? '<p class="message">' . escape($message) . '</p>' : '';

?>
<h2>Fruit toevoegen</h2>
<p><a class="back-button" href="?controller=fruits_index">Back</a></p>
<?= $messageHtml ?>
<form method="post" action="?controller=fruits_add">
    <label for="name">name</label>
    <input id="name" name="name" value="<?= escape($name) ?>">
    <label for="color">color</label>
    <input id="color" name="color" value="<?= escape($color) ?>">
    <label for="sweetness">sweetness</label>
    <input id="sweetness" name="sweetness" value="<?= escape($sweetness) ?>">
    <label for="season">season</label>
    <input id="season" name="season" value="<?= escape($season) ?>">
    <button class="add-button" type="submit">Add</button>
</form>
