<?php
$databaseConfig = require 'app/config/database.php';

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

$hasValidId = is_numeric($id);
$fruit = null;
$sql = '';
$aggregateRow = [];
$seasons = [];

if ($hasValidId) {
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
    while ($seasonRow = mysqli_fetch_assoc($seasonResult)) {
        $seasons[] = $seasonRow;
    }
    mysqli_free_result($seasonResult);
}

$listItemsHtml = '';
$pageNoteExtraHtml = '';
if ($fruit !== null) {
    foreach ($fruit as $column => $value) {
        $displayValue = $value === null ? 'NULL (niet ingevuld)' : (string) $value;
        $listItemsHtml .= '<li><strong>' . escape($column) . '</strong>: ' . escape($displayValue) . '</li>';
    }

    foreach ($aggregateRow as $column => $value) {
        $displayValue = $value === null ? 'NULL' : (string) $value;
        $pageNoteExtraHtml .= '<p>' . escape($column) . ' = ' . escape($displayValue) . '</p>';
    }
    foreach ($seasons as $seasonRow) {
        $pageNoteExtraHtml .= '<p>' . escape($seasonRow['season']) . ' = ' . escape($seasonRow['total']) . '</p>';
    }
}

?>
<?php if (!$hasValidId): ?>
    <p class="message">Geen id. Open een rij via View, bijvoorbeeld ?controller=fruits_show&amp;id=1.</p>
<?php else: ?>
    <h2>Eén fruitsoort</h2>
    <p><a class="back-button" href="?controller=fruits_index">Back</a></p>
    <?php if ($fruit === null): ?>
        <p class="message">Geen record met id <?= $id ?>.</p>
    <?php else: ?>
        <ul><?= $listItemsHtml ?></ul>
    <?php endif; ?>

<?php endif; ?>
