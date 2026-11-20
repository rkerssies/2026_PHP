<?php
// Kijkt of deze aanroep een token moet hebben.
// De lijst staat in config/jwt.php. Staat de methode er niet in, dan gebeurt er niets.
function jwtProtect(string $controller): void
{
    $method = 'GET';
    if (isset($_SERVER['REQUEST_METHOD'])) {
        $method = strtoupper((string) $_SERVER['REQUEST_METHOD']);
    }
    if (!jwtMustCheck($controller, $method)) {
        return;
    }

    $request = [
        'get' => $_GET,
        'post' => [],
        'put' => [],
        'patch' => [],
        'delete' => [],
    ];

    // Stap 1. Token lezen uit de url (?token=) of uit Authorization: Bearer ...
    $token = jwtReadToken();
    if ($token === '') {
        jsonResponse(null, false, 'JWT', 'Token ontbreekt.', [], 401, $request);
    }

    // Stap 2. Handtekening controleren met app_key uit de config.
    $secret = (defined('CONFIG') && isset(CONFIG['app_key'])) ? (string) CONFIG['app_key'] : '';
    $payload = jwtRead($token, $secret);
    if ($payload === null) {
        jsonResponse(null, false, 'JWT', 'Token is ongeldig.', [], 401, $request);
    }

    // Stap 3. De tijd in due mag niet voorbij zijn.
    $due = isset($payload['due']) ? strtotime((string) $payload['due']) : false;
    if ($due === false || $due < time()) {
        jsonResponse(null, false, 'JWT', 'Token is verlopen.', [], 401, $request);
    }
}

// true als controller + methode in config/jwt.php staan.
function jwtMustCheck(string $controller, string $method): bool
{
    if (!defined('CONFIG') || !isset(CONFIG['jwt_guard'][$controller]) || !is_array(CONFIG['jwt_guard'][$controller])) {
        return false;
    }

    foreach (CONFIG['jwt_guard'][$controller] as $guardedMethod) {
        if (strtoupper((string) $guardedMethod) === $method) {
            return true;
        }
    }

    return false;
}

// Email en password uit de body. Drie manieren, van eenvoudig naar de API-vorm.
function jwtLoginInput(): array
{
    $body = jwtBody();
    if (isset($body['email']) || isset($body['password'])) {
        return $body;
    }
    if (isset($body['request']['post']) && is_array($body['request']['post'])) {
        return $body['request']['post'];
    }
    if ($_POST !== []) {
        return $_POST;
    }

    return [];
}

function jwtBody(): array
{
    $raw = file_get_contents('php://input');
    if (!is_string($raw) || trim($raw) === '') {
        return [];
    }
    $decoded = json_decode($raw, true);

    return is_array($decoded) ? $decoded : [];
}

// Eerst ?token= in de url. Dat is het makkelijkst om te testen.
// Daarna de header Authorization: Bearer het-token.
function jwtReadToken(): string
{
    if (isset($_GET['token']) && is_string($_GET['token']) && $_GET['token'] !== '') {
        return $_GET['token'];
    }

    $header = '';
    if (isset($_SERVER['HTTP_AUTHORIZATION']) && is_string($_SERVER['HTTP_AUTHORIZATION'])) {
        $header = $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && is_string($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    } elseif (function_exists('getallheaders')) {
        $headers = getallheaders();
        if (is_array($headers)) {
            foreach ($headers as $name => $value) {
                if (is_string($name) && strcasecmp($name, 'Authorization') === 0 && is_string($value)) {
                    $header = $value;
                }
            }
        }
    }

    if (preg_match('/\ABearer\s+(\S+)\z/i', trim($header), $match) === 1) {
        return $match[1];
    }

    return '';
}

// Een token is drie stukken, gescheiden door een punt:
// header (soort token) . inhoud (email, name, created_at, due) . handtekening.
function jwtCreate(array $payload, string $secret): string
{
    $header = jwtEncodePart(['alg' => 'HS256', 'typ' => 'JWT']);
    $body = jwtEncodePart($payload);
    $signature = jwtEncodeRaw(hash_hmac('sha256', $header . '.' . $body, $secret, true));

    return $header . '.' . $body . '.' . $signature;
}

// Geeft de inhoud terug, of null als de handtekening niet klopt.
function jwtRead(string $token, string $secret): ?array
{
    $parts = explode('.', $token);
    if (count($parts) !== 3 || $secret === '') {
        return null;
    }

    $signature = jwtEncodeRaw(hash_hmac('sha256', $parts[0] . '.' . $parts[1], $secret, true));
    if (!hash_equals($signature, $parts[2])) {
        return null;
    }

    $payload = json_decode(jwtDecodePart($parts[1]), true);
    if (!is_array($payload)) {
        return null;
    }

    return $payload;
}

function jwtEncodePart(array $value): string
{
    $json = json_encode($value, JSON_UNESCAPED_UNICODE);
    if (!is_string($json)) {
        return '';
    }

    return jwtEncodeRaw($json);
}

function jwtEncodeRaw(string $value): string
{
    return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
}

function jwtDecodePart(string $value): string
{
    $remainder = strlen($value) % 4;
    if ($remainder > 0) {
        $value .= str_repeat('=', 4 - $remainder);
    }
    $decoded = base64_decode(strtr($value, '-_', '+/'), true);

    return is_string($decoded) ? $decoded : '';
}
