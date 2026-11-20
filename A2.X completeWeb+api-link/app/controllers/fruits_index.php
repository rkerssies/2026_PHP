<?php
$databaseConfig = CONFIG['database'];

$sql = 'SELECT * FROM `fruits` ORDER BY `name` ASC';

$connection = dbConnect();
$result = dbQuery($connection, $sql);

if ($result === false) {
    echo '<p>De query is mislukt.</p>';
    return;
}

$rowCount = count($result);
$columns = $rowCount > 0 ? array_keys($result[0]) : [];
$fruits = $result;

$headerCellsHtml = '';
$bodyRowsHtml = '';
if ($rowCount > 0) {
    foreach ($columns as $columnName) {
        $headerCellsHtml .= '<th class="' . escape($columnName) . '" scope="col">' . escape($columnName) . '</th>';
    }
    $headerCellsHtml .= '<th class="view" scope="col">View</th>';
    $headerCellsHtml .= '<th class="update" scope="col">Update</th>';
    $headerCellsHtml .= '<th class="delete" scope="col">Delete</th>';

    foreach ($fruits as $fruit) {
        $cellsHtml = '';
        foreach ($fruit as $column => $value) {
            $displayValue = $value === null ? 'NULL (niet ingevuld)' : (string) $value;
            $cellsHtml .= '<td class="' . escape($column) . '">' . escape($displayValue) . '</td>';
        }
        $cellsHtml .= '<td class="view"><a class="view-button" href="?controller=fruits_show&id=' . escape($fruit['id']) . '">View</a></td>';
        $cellsHtml .= '<td class="update"><a class="update-button" href="?controller=fruits_update&id=' . escape($fruit['id']) . '">Update</a></td>';
        $cellsHtml .= '<td class="delete"><a class="delete-button" href="?controller=fruits_delete&id=' . escape($fruit['id']) . '">Delete</a></td>';
        $bodyRowsHtml .= '<tr>' . $cellsHtml . '</tr>';
    }
}

?>
<h2>Alle fruitsoorten</h2>
<p><a class="add-button" href="?controller=fruits_add">Add</a></p>
<?php if ($rowCount === 0): ?>
    <p>Geen fruitsoorten gevonden. De query is gelukt, maar de tabel is leeg.</p>
<?php else: ?>
    <p>Aantal records: <?= $rowCount ?></p>
    <div class="fruit-table-wrapper">
        <table>
            <caption>Alle fruitsoorten</caption>
            <thead><tr><?= $headerCellsHtml ?></tr></thead>
            <tbody><?= $bodyRowsHtml ?></tbody>
        </table>
    </div>
<?php endif; ?>
