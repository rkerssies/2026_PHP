<?php
// URL: index.php?controller=jwt  (POST met email en password)
$method = 'GET';
if (isset($_SERVER['REQUEST_METHOD'])) {
    $method = strtoupper((string) $_SERVER['REQUEST_METHOD']);
}
$body = jwtBody();
$wrapped = (isset($body['request']) && is_array($body['request'])) ? $body['request'] : [];
if (isset($wrapped['post']) && is_array($wrapped['post'])) {
    $post = $wrapped['post'];
} elseif ($method === 'POST' && $wrapped === [] && $body !== []) {
    $post = $body;
} elseif ($_POST !== []) {
    $post = $_POST;
} else {
    $post = [];
}

$request = [
    'get' => $_GET,
    'post' => $post,
    'put' => [],
    'patch' => [],
    'delete' => [],
];

if ($method !== 'POST') {
    jsonResponse(null, false, 'JWT', 'Methode niet toegestaan.', [], 405, $request);
}

$fields = [];
if (array_key_exists('email', $post)) {
    $fields['email'] = $post['email'];
}
if (array_key_exists('password', $post)) {
    $fields['password'] = $post['password'];
}

$validation = validationCheck('jwt', $fields, true);
if ($validation !== []) {
    jsonResponse(null, false, 'JWT', 'Controleer de invoer.', [], 400, $request, $validation);
}

$databaseConfig = CONFIG['database'];
$connection = dbConnect();
// mysqli_set_charset($connection, $databaseConfig['charset']);
if ($connection === false) {
    jsonResponse(null, false, 'JWT', 'Geen databaseverbinding.', [], 500, $request);
}

$email = trim((string) $fields['email']);
$password = (string) $fields['password'];
$emailSql = mysqli_real_escape_string($connection, $email);
$sql = 'SELECT `name`, `email`, `password` FROM `users` WHERE `email` = \'' . $emailSql . '\' LIMIT 1';

// De query wordt hier, in de controller, uitgevoerd.
$rows = dbQuery($connection, $sql);
if ($rows === false) {
    jsonResponse(null, false, 'JWT', dbQueryInfo()['error'], [], 500, $request);
}

$known = is_array($rows) && isset($rows[0]) ? $rows[0] : null;
$stored = is_array($known) ? strtolower((string) $known['password']) : '';
if (!is_array($known) || !hash_equals($stored, sha1($password))) {
    jsonResponse(null, false, 'JWT', 'E-mail of wachtwoord is onjuist.', [], 401, $request);
}

$createdAt = date('Y-m-d H:i:s');
$dueSeconds = (defined('CONFIG') && isset(CONFIG['jwt_due'])) ? (int) CONFIG['jwt_due'] : 3600;
$due = date('Y-m-d H:i:s', time() + $dueSeconds);
$payload = [
    'email' => (string) $known['email'],
    'name' => (string) $known['name'],
    'created_at' => $createdAt,
    'due' => $due,
];
$secret = (defined('CONFIG') && isset(CONFIG['app_key'])) ? (string) CONFIG['app_key'] : '';
$token = jwtCreate($payload, $secret);

jsonResponse(
    [
        'token' => $token,
        'email' => $payload['email'],
        'name' => $payload['name'],
        'created_at' => $createdAt,
        'due' => $due,
    ],
    true,
    'JWT',
    'Token aangemaakt.',
    [],
    200,
    $request
);
