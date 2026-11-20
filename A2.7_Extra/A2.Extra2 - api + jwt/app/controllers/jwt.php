<?php
// Token ophalen: POST index.php?controller=jwt
// Stuur email en password mee, bijvoorbeeld:
// { "email": "admin@school.nl", "password": "password" }

$method = 'GET';
if (isset($_SERVER['REQUEST_METHOD'])) {
    $method = strtoupper((string) $_SERVER['REQUEST_METHOD']);
}
$post = jwtLoginInput();
$request = [
    'get' => $_GET,
    'post' => $post,
    'put' => [],
    'patch' => [],
    'delete' => [],
];

// Stap 1. Alleen POST mag een token vragen.
if ($method !== 'POST') {
    jsonResponse(null, false, 'JWT', 'Methode niet toegestaan.', [], 405, $request);
}

// Stap 2. Email en password controleren.
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

// Stap 3. Gebruiker zoeken op email.
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

// Stap 4. Wachtwoord vergelijken. In de database staat de sha1 van het wachtwoord.
$user = is_array($rows) && isset($rows[0]) ? $rows[0] : null;
$storedPassword = is_array($user) ? strtolower((string) $user['password']) : '';
if (!is_array($user) || !hash_equals($storedPassword, sha1($password))) {
    jsonResponse(null, false, 'JWT', 'E-mail of wachtwoord is onjuist.', [], 401, $request);
}

// Stap 5. Token maken. due is nu + jwt_due seconden uit de config.
$createdAt = date('Y-m-d H:i:s');
$dueSeconds = (defined('CONFIG') && isset(CONFIG['jwt_due'])) ? (int) CONFIG['jwt_due'] : 3600;
$due = date('Y-m-d H:i:s', time() + $dueSeconds);
$secret = (defined('CONFIG') && isset(CONFIG['app_key'])) ? (string) CONFIG['app_key'] : '';
$token = jwtCreate([
    'email' => (string) $user['email'],
    'name' => (string) $user['name'],
    'created_at' => $createdAt,
    'due' => $due,
], $secret);

// Stap 6. Token teruggeven. Plak dit token bij een beveiligde aanroep.
jsonResponse(
    [
        'token' => $token,
        'email' => (string) $user['email'],
        'name' => (string) $user['name'],
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
