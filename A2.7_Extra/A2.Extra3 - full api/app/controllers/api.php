<?php
// URL: index.php?controller=api&table=fruits
$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
$request = apiCollectRequest($method);

$databaseConfig = CONFIG['database'];
$connection = dbConnect();
// mysqli_set_charset($connection, $databaseConfig['charset']);
if ($connection === false) {
    jsonResponse(null, false, 'API', 'Geen databaseverbinding.', [], 500, $request);
}

if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'], true)) {
    jsonResponse(null, false, 'API', 'Methode niet toegestaan.', [], 405, $request);
}

/////////////////////////////////////////////////////////////
// Tabelnaam + kolommen ophalen en controleren
/////////////////////////////////////////////////////////////
$table = (isset($_GET['table']) && is_string($_GET['table'])) ? $_GET['table'] : '';
if ($table === '' || preg_match('/\A[A-Za-z0-9_]+\z/', $table) !== 1) {
    jsonResponse(null, false, 'API', 'Geef een tabelnaam mee.', ['table' => 'table is verplicht.'], 400, $request);
}

$tables = apiTableNames($connection);
if (!in_array($table, $tables, true)) {
    jsonResponse(null, false, 'API', 'Tabel niet gevonden.', ['table' => 'Tabel bestaat niet.'], 404, $request);
}

$columns = apiColumnNames($connection, $table);
if (!in_array('id', $columns, true)) {
    jsonResponse(null, false, 'API', 'Deze tabel heeft geen id.', ['id' => 'Kolom id ontbreekt.'], 400, $request);
}

$tableSql = '`' . $table . '`';
$id = apiReadId();

/////////////////////////////////////////////////////////////
// Eén record ophalen
/////////////////////////////////////////////////////////////
if ($method === 'GET' && $id !== null) {
    $sql = 'SELECT * FROM ' . $tableSql . ' WHERE `id` = ' . $id . ' LIMIT 1';

    // De query wordt hier, in de controller, uitgevoerd.
    $rows = dbQuery($connection, $sql);
    if ($rows === false) {
        jsonResponse(null, false, 'API', dbQueryInfo()['error'], [], 500, $request);
    }
    if ($rows === []) {
        jsonResponse(null, false, 'API', 'Record niet gevonden.', [], 404, $request);
    }
    jsonResponse($rows[0], true, 'API', 'Record opgehaald.', [], 200, $request);
}

/////////////////////////////////////////////////////////////
// Eerste of laatste record. first = laagste id, last = hoogste id.
/////////////////////////////////////////////////////////////
$edge = ($method === 'GET') ? apiReadEdge() : null;
if ($edge !== null) {
    $direction = $edge === 'last' ? 'DESC' : 'ASC';
    $sql = 'SELECT * FROM ' . $tableSql . ' ORDER BY `id` ' . $direction . ' LIMIT 1';

    // De query wordt hier, in de controller, uitgevoerd.
    $rows = dbQuery($connection, $sql);
    if ($rows === false) {
        jsonResponse(null, false, 'API', dbQueryInfo()['error'], [], 500, $request);
    }
    if ($rows === []) {
        jsonResponse(null, false, 'API', 'Record niet gevonden.', [], 404, $request);
    }
    $message = $edge === 'last' ? 'Laatste record opgehaald.' : 'Eerste record opgehaald.';
    jsonResponse($rows[0], true, 'API', $message, [], 200, $request);
}

/////////////////////////////////////////////////////////////
// Alle records ophalen
/////////////////////////////////////////////////////////////
if ($method === 'GET') {
    $sql = 'SELECT * FROM ' . $tableSql . ' ORDER BY `id`';

    // De query wordt hier, in de controller, uitgevoerd.
    $rows = dbQuery($connection, $sql);
    if ($rows === false) {
        jsonResponse(null, false, 'API', dbQueryInfo()['error'], [], 500, $request);
    }

    jsonResponse($rows, true, 'API', 'Records opgehaald.', [], 200, $request);
}

/////////////////////////////////////////////////////////////
// Record toevoegen
/////////////////////////////////////////////////////////////
if ($method === 'POST') {
    $errors = [];
    $fields = apiFields($request['post'], $columns, $errors);
    if ($request['post'] === []) {
        $errors['post'] = 'request.post is verplicht.';
    }
    if ($errors !== []) {
        jsonResponse(null, false, 'API', 'Controleer de invoer.', $errors, 400, $request);
    }

    $names = [];
    $values = [];
    foreach ($fields as $column => $value) {
        $names[] = '`' . $column . '`';
        $values[] = apiSqlValue($connection, $value);
    }
    $sql = 'INSERT INTO ' . $tableSql . ' (' . implode(', ', $names) . ') VALUES (' . implode(', ', $values) . ')';

    // De query wordt hier, in de controller, uitgevoerd.
    $saved = dbQuery($connection, $sql);
    if ($saved !== true) {
        jsonResponse(null, false, 'API', dbQueryInfo()['error'], [], 500, $request);
    }

    $newId = (int) dbQueryInfo()['insert_id'];
    $sql = 'SELECT * FROM ' . $tableSql . ' WHERE `id` = ' . $newId . ' LIMIT 1';

    // De query wordt hier, in de controller, uitgevoerd.
    $created = dbQuery($connection, $sql);
    $data = is_array($created) && isset($created[0]) ? $created[0] : array_merge(['id' => $newId], $fields);
    jsonResponse($data, true, 'API', 'Record toegevoegd.', [], 201, $request);
}

if ($id === null) {
    jsonResponse(null, false, 'API', 'id is verplicht.', ['id' => 'id is verplicht.'], 400, $request);
}

$sql = 'SELECT `id` FROM ' . $tableSql . ' WHERE `id` = ' . $id . ' LIMIT 1';

// De query wordt hier, in de controller, uitgevoerd.
$existing = dbQuery($connection, $sql);
if ($existing === false) {
    jsonResponse(null, false, 'API', dbQueryInfo()['error'], [], 500, $request);
}
if ($existing === []) {
    jsonResponse(null, false, 'API', 'Record niet gevonden.', [], 404, $request);
}

/////////////////////////////////////////////////////////////
// Record verwijderen
/////////////////////////////////////////////////////////////
if ($method === 'DELETE') {
    $sql = 'DELETE FROM ' . $tableSql . ' WHERE `id` = ' . $id;

    // De query wordt hier, in de controller, uitgevoerd.
    $deleted = dbQuery($connection, $sql);
    if ($deleted !== true) {
        jsonResponse(null, false, 'API', dbQueryInfo()['error'], [], 500, $request);
    }
    jsonResponse(['id' => $id], true, 'API', 'Record verwijderd.', [], 200, $request);
}

/////////////////////////////////////////////////////////////
// Record wijzigen
/////////////////////////////////////////////////////////////
$errors = [];
$fields = apiFields($request['put'], $columns, $errors);
if ($request['put'] === []) {
    $errors['put'] = 'request.put is verplicht.';
}
unset($fields['id']);
if ($fields === [] && !isset($errors['put'])) {
    $errors['put'] = 'Geen kolommen om te wijzigen.';
}
if ($errors !== []) {
    jsonResponse(null, false, 'API', 'Controleer de invoer.', $errors, 400, $request);
}

$assignments = [];
foreach ($fields as $column => $value) {
    $assignments[] = '`' . $column . '` = ' . apiSqlValue($connection, $value);
}
$sql = 'UPDATE ' . $tableSql . ' SET ' . implode(', ', $assignments) . ' WHERE `id` = ' . $id;

// De query wordt hier, in de controller, uitgevoerd.
$updated = dbQuery($connection, $sql);
if ($updated !== true) {
    jsonResponse(null, false, 'API', dbQueryInfo()['error'], [], 500, $request);
}

$data = array_merge(['id' => $id], $fields);
jsonResponse($data, true, 'API', 'Record gewijzigd.', [], 200, $request);

//////////////////////////////////////////////////////////////
// Functions

// Request-deel ophalen
/////////////////////////////////////////////////////////////
function apiCollectRequest(string $method): array
{
    $body = [];
    $raw = file_get_contents('php://input');
    if (is_string($raw) && trim($raw) !== '') {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $body = $decoded;
        }
    }

    $wrapped = (isset($body['request']) && is_array($body['request'])) ? $body['request'] : [];
    $post = apiRequestPart($wrapped, $body, 'post', $method);
    $put = apiRequestPart($wrapped, $body, 'put', $method);
    $delete = apiRequestPart($wrapped, $body, 'delete', $method);

    if ($post === [] && $_POST !== []) {
        $post = $_POST;
    }

    return [
        'get' => $_GET,
        'post' => $post,
        'put' => $put,
        'delete' => $delete,
    ];
}

// Request-deel ophalen
/////////////////////////////////////////////////////////////
function apiRequestPart(array $wrapped, array $body, string $key, string $method): array
{
    if (isset($wrapped[$key]) && is_array($wrapped[$key])) {
        return $wrapped[$key];
    }
    if ($wrapped === [] && strtoupper($key) === $method && $body !== []) {
        return $body;
    }

    return [];
}

// Tabelnamen ophalen
/////////////////////////////////////////////////////////////
function apiTableNames($connection): array
{
    $sql = 'SHOW TABLES';

    // De query wordt hier, in de controller, uitgevoerd.
    $rows = dbQuery($connection, $sql);
    if (!is_array($rows)) {
        return [];
    }

    $tables = [];
    foreach ($rows as $row) {
        $tables[] = (string) array_values($row)[0];
    }

    return $tables;
}

// Kolommen ophalen
/////////////////////////////////////////////////////////////
function apiColumnNames($connection, string $table): array
{
    $sql = 'SHOW COLUMNS FROM `' . $table . '`';

    // De query wordt hier, in de controller, uitgevoerd.
    $rows = dbQuery($connection, $sql);
    if (!is_array($rows)) {
        return [];
    }

    $columns = [];
    foreach ($rows as $row) {
        $columns[] = (string) $row['Field'];
    }

    return $columns;
}

// select=first of select=last. Ook first=1 en last=1.
/////////////////////////////////////////////////////////////
function apiReadEdge(): ?string
{
    $select = '';
    if (isset($_GET['select']) && is_string($_GET['select'])) {
        $select = strtolower(trim($_GET['select']));
    }
    if ($select === 'first' || $select === 'last') {
        return $select;
    }
    if ($select !== '') {
        jsonResponse(null, false, 'API', 'select is first of last.', ['select' => 'select is first of last.'], 400, apiCollectRequest(strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'))));
    }

    $first = isset($_GET['first']) && is_string($_GET['first']) && $_GET['first'] !== '' && $_GET['first'] !== '0';
    $last = isset($_GET['last']) && is_string($_GET['last']) && $_GET['last'] !== '' && $_GET['last'] !== '0';
    if ($first && $last) {
        jsonResponse(null, false, 'API', 'Kies first of last.', ['select' => 'Kies first of last.'], 400, apiCollectRequest(strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'))));
    }
    if ($first) {
        return 'first';
    }
    if ($last) {
        return 'last';
    }

    return null;
}

// ID ophalen en controleren
/////////////////////////////////////////////////////////////
function apiReadId(): ?int
{
    if (!isset($_GET['id']) || !is_string($_GET['id']) || $_GET['id'] === '') {
        return null;
    }
    if (!is_numeric($_GET['id'])) {
        jsonResponse(null, false, 'API', 'id moet een getal zijn.', ['id' => 'id moet een getal zijn.'], 400, apiCollectRequest(strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'))));
    }

    return (int) $_GET['id'];
}

// Kolommen en waarden ophalen en controleren
/////////////////////////////////////////////////////////////
function apiFields(array $input, array $columns, array &$errors): array
{
    $fields = [];
    foreach ($input as $column => $value) {
        if (!is_string($column) || !in_array($column, $columns, true)) {
            $errors[$column] = 'Onbekende kolom.';
            continue;
        }
        if (is_array($value)) {
            $errors[$column] = 'Kolomwaarde moet een enkele waarde zijn.';
            continue;
        }
        $fields[$column] = $value;
    }

    return $fields;
}

// Waarde omzetten naar SQL-waarde
/////////////////////////////////////////////////////////////
function apiSqlValue($connection, mixed $value): string
{
    if ($value === null) {
        return 'NULL';
    }
    if (is_bool($value)) {
        return $value ? '1' : '0';
    }
    if (is_int($value) || is_float($value)) {
        return (string) $value;
    }

    return "'" . mysqli_real_escape_string($connection, (string) $value) . "'";
}
