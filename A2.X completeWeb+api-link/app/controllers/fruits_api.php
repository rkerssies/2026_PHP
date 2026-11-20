<?php
// URL: index.php?controller=fruits_api
$databaseConfig = CONFIG['database'];

// SELECT * haalt ALLE kolommen en ALLE records uit fruits op.
// De API sorteert altijd op id, oplopend.
$sql = 'SELECT * FROM `fruits` ORDER BY `name` ASC';

$connection = dbConnect();
// mysqli_set_charset($connection, $databaseConfig['charset']);

if ($connection === false) {
    jsonResponse(null, false, 'Fruit-API', 'Geen databaseverbinding.', [], 500);
}

// De query wordt hier, in de controller, uitgevoerd.
$result = dbQuery($connection, $sql);
if ($result === false) {
    jsonResponse(null, false, 'Fruit-API', dbQueryInfo()['error'], [], 500);
}

jsonResponse($result, true, 'Fruit-API', 'Alle fruitrecords.', [], 200);
