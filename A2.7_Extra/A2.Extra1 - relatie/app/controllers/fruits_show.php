<?php
// URL: index.php?controller=fruits_show&id=1
// $_GET['id'] is de id uit de querystring.
// Plak de id uit een formulier op dezelfde plek met: $id = $_POST['id'];
$databaseConfig = require __DIR__ . '/../config/database.php';
$escape = static function ($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$getId = (isset($_GET['id']) && is_string($_GET['id'])) ? $_GET['id'] : '';
$postId = (isset($_POST['id']) && is_string($_POST['id'])) ? $_POST['id'] : '';
$id = $getId !== '' ? $getId : $postId;

if (!is_numeric($id)) {
    echo '<p class="message">Geen id. Open een rij via View, bijvoorbeeld ?controller=fruits_show&amp;id=1.</p>';
    return;
}

$id = (int) $id;
$sql = 'SELECT * FROM `fruits` WHERE `id` = ' . $id;
$aggregateSql = 'SELECT COUNT(*) AS total, 
                AVG(`sweetness`) AS avg_sweetness, 
                MIN(`sweetness`) AS min_sweetness, 
                MAX(`sweetness`) AS max_sweetness, 
                SUM(`sweetness`) AS sum_sweetness 
                FROM `fruits`';
$seasonSql = 'SELECT `season`, COUNT(*) AS total 
                FROM `fruits` 
                GROUP BY `season` 
                ORDER BY `season` ASC';

$connection = mysqli_connect(
    $databaseConfig['host'],
    $databaseConfig['username'],
    $databaseConfig['password'],
    $databaseConfig['database'],
    $databaseConfig['port']
);
$result = mysqli_query($connection, $sql);
$fruit = mysqli_fetch_assoc($result);
mysqli_free_result($result);

$aggregateResult = mysqli_query($connection, $aggregateSql);
$aggregateRow = mysqli_fetch_assoc($aggregateResult);
mysqli_free_result($aggregateResult);

$seasonResult = mysqli_query($connection, $seasonSql);
$seasons = [];
while ($seasonRow = mysqli_fetch_assoc($seasonResult)) {
    $seasons[] = $seasonRow;
}
mysqli_free_result($seasonResult);

//// >>>> relational data <<<< ////
$favoriteSql = 'SELECT `name`, `email` FROM `users` WHERE `favorite_fruit_id` = ' . $id . ' ORDER BY `name` ASC';
// $favoriteSql = 'SELECT `users`.`name`, `users`.`email` FROM `users` INNER JOIN `fruits` ON `users`.`favorite_fruit_id` = `fruits`.`id` WHERE `fruits`.`id` = ' . $id . ' ORDER BY `users`.`name` ASC';
//// >>>> relational data <<<< ////

$favoriteCountSql = 'SELECT COUNT(*) AS favorite_total FROM `users` WHERE `favorite_fruit_id` = ' . $id;
$favoriteResult = mysqli_query($connection, $favoriteSql);
$favoriteUsers = [];
while ($favoriteRow = mysqli_fetch_assoc($favoriteResult)) {
    $favoriteUsers[] = $favoriteRow;
}
mysqli_free_result($favoriteResult);
$favoriteCountResult = mysqli_query($connection, $favoriteCountSql);
$favoriteCountRow = mysqli_fetch_assoc($favoriteCountResult);
mysqli_free_result($favoriteCountResult);

$pageHtml = '<h2>Eén fruitsoort</h2>';
$pageHtml .= '<p><a class="back-button" href="?controller=fruits_index">Back</a></p>';

// $noteHtml = '<div class="page-note">';
// $noteHtml .= '<p>$_GET["id"] = ' . $escape($getId === '' ? '(mist)' : $getId) . '</p>';
// $noteHtml .= '<p>$_POST["id"] = ' . $escape($postId === '' ? '(mist)' : $postId) . '</p>';

if ($fruit === null) {
    $pageHtml .= '<p class="message">Geen record met id ' . $id . '.</p>';
    // $noteHtml .= '<pre><code>' . $escape($sql) . '</code></pre>';
    echo $pageHtml;
    return;
}

////////////////////////////////////////////////////////////
$pageHtml .= '<ul>';
foreach ($fruit as $column => $value) {
    $displayValue = $value === null ? 'NULL (niet ingevuld)' : (string) $value;
    $pageHtml .= '<li><strong>' . $escape($column) . '</strong>: ' . $escape($displayValue) . '</li>';
}
$pageHtml .= '</ul>';

$pageHtml .= '<h3>Favoriet bij</h3>';
$pageHtml .= '<p>Aantal gebruikers: ' . (int) $favoriteCountRow['favorite_total'] . '</p>';
if ($favoriteUsers === []) {
    $pageHtml .= '<p>Niemand heeft dit fruit als favoriet.</p>';
} else {
    $pageHtml .= '<ul>';
    foreach ($favoriteUsers as $favoriteUser) {
        $pageHtml .= '<li>' . $escape($favoriteUser['name']) . ' (' . $escape($favoriteUser['email']) . ')</li>';
    }
    $pageHtml .= '</ul>';
}

echo $pageHtml;
