<?php
// URL: index.php?controller=fruits_show&id=1
// $_GET['id'] is de id uit de querystring.
$databaseConfig = CONFIG['database'];

$getId = '';
if (isset($_GET['id'])) {
    $getId = $_GET['id'];
}
$postId = '';
if (isset($_POST['id'])) {
    $postId = $_POST['id'];
}
$id = $getId;
if ($getId === '') {
    $id = $postId;
}

if (!is_numeric($id)) {
    echo '<p class="message">Geen id. Open een rij via View, bijvoorbeeld ?controller=fruits_show&amp;id=1.</p>';
    return;
}

$id = (int) $id;
$sql = 'SELECT * FROM `fruits` WHERE `id` = ' . $id;

$connection = dbConnect();

$result = dbQuery($connection, $sql);
$fruit = (is_array($result) && isset($result[0])) ? $result[0] : null;

//// >>>> relational data <<<< ////
$favoriteSql = 'SELECT `name`, `email` FROM `users` WHERE `favorite_fruit_id` = ' . $id . ' ORDER BY `name` ASC';
// $favoriteSql = 'SELECT `users`.`name`, `users`.`email` FROM `users` INNER JOIN `fruits` ON `users`.`favorite_fruit_id` = `fruits`.`id` WHERE `fruits`.`id` = ' . $id . ' ORDER BY `users`.`name` ASC';
//// >>>> relational data <<<< ////

$favoriteCountSql = 'SELECT COUNT(*) AS favorite_total FROM `users` WHERE `favorite_fruit_id` = ' . $id;

$favoriteResult = dbQuery($connection, $favoriteSql);
$favoriteUsers = is_array($favoriteResult) ? $favoriteResult : [];

$favoriteCountResult = dbQuery($connection, $favoriteCountSql);
$favoriteCountRow = (is_array($favoriteCountResult) && isset($favoriteCountResult[0]))
    ? $favoriteCountResult[0]
    : ['favorite_total' => 0];

?>
<h2>Eén fruitsoort</h2>
<p><a class="back-button" href="?controller=fruits_index">Back</a></p>
<?php if ($fruit === null): ?>
    <p class="message">Geen record met id <?= $id ?>.</p>
<?php else: ?>
    <ul>
        <?php foreach ($fruit as $column => $value): ?>
            <?php if ($column === 'href' && is_string($value) && $value !== ''): ?>
                <li><strong><?= escape($column) ?></strong>: <?= fruitImage($value, (string) $fruit['name']) ?></li>
            <?php else: ?>
                <?php $displayValue = $value === null ? 'NULL (niet ingevuld)' : (string) $value; ?>
                <li><strong><?= escape($column) ?></strong>: <?= escape($displayValue) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <h3>Favoriet bij</h3>
    <p>Aantal gebruikers: <?= (int) $favoriteCountRow['favorite_total'] ?></p>
    <?php if ($favoriteUsers === []): ?>
        <p>Niemand heeft dit fruit als favoriet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($favoriteUsers as $favoriteUser): ?>
                <li><?= escape($favoriteUser['name']) ?> (<?= escape($favoriteUser['email']) ?>)</li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>
