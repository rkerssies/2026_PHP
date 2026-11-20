<?php
$databaseConfig = CONFIG['database'];

$statsSql = 'SELECT COUNT(*) AS total, 
                    AVG(`sweetness`) AS avg_sweetness, 
                    MIN(`sweetness`) AS min_sweetness, 
                    MAX(`sweetness`) AS max_sweetness, 
                    SUM(`sweetness`) AS sum_sweetness 
             FROM `fruits`';

$bySeasonSql = 'SELECT `season`, COUNT(*) AS total 
                FROM `fruits` 
                GROUP BY `season` 
                ORDER BY `season` ASC';

$bySweetnessSql = 'SELECT `sweetness`, COUNT(*) AS total 
                   FROM `fruits` 
                   GROUP BY `sweetness` 
                   ORDER BY `sweetness` ASC';

$connection = dbConnect();

$statsRow = [];
$statsResult = dbQuery($connection, $statsSql);
if (is_array($statsResult) && isset($statsResult[0])) {
    $statsRow = $statsResult[0];
}

$seasons = [];
$seasonResult = dbQuery($connection, $bySeasonSql);
if (is_array($seasonResult)) {
    $seasons = $seasonResult;
}

$sweetnessCounts = [];
$sweetnessResult = dbQuery($connection, $bySweetnessSql);
if (is_array($sweetnessResult)) {
    $sweetnessCounts = $sweetnessResult;
}

$statsItemsHtml = '';
foreach ($statsRow as $column => $value) {
    $displayValue = $value === null ? 'NULL' : (string) $value;
    $statsItemsHtml .= '<li><strong>' . escape($column) . '</strong>: ' . escape($displayValue) . '</li>';
}

$seasonItemsHtml = '';
foreach ($seasons as $seasonRow) {
    $seasonItemsHtml .= '<li><strong>' . escape($seasonRow['season']) . '</strong>: ' . escape($seasonRow['total']) . '</li>';
}

$sweetnessItemsHtml = '';
foreach ($sweetnessCounts as $sweetnessRow) {
    $sweetnessItemsHtml .= '<li><strong>sweetness ' . escape($sweetnessRow['sweetness']) . '</strong>: ' . escape($sweetnessRow['total']) . '</li>';
}

?>
<h2>Aggregates</h2>

<h3>Stats</h3>
<ul><?= $statsItemsHtml ?></ul>

<h3>Per seizoen</h3>
<ul><?= $seasonItemsHtml ?></ul>

<h3>Aantallen per sweetness (ORDER BY sweetness)</h3>
<ul><?= $sweetnessItemsHtml ?></ul>
