<?php
// Controleert het token alleen als dit verzoek in config/jwt.php staat.
function jwtProtect(string $controller): void
{
    $requestMethod = 'GET';
    if (isset($_SERVER['REQUEST_METHOD'])) {
        $requestMethod = strtoupper((string) $_SERVER['REQUEST_METHOD']);
    }
    $method = jwtEffectiveMethod($requestMethod);
    $table = (isset($_GET['table']) && is_string($_GET['table'])) ? $_GET['table'] : '';
    if (!jwtMustCheck($controller, $method, $table)) {
        return;
    }

    $request = [
        'get' => $_GET,
        'post' => [],
        'put' => [],
        'patch' => [],
        'delete' => [],
    ];
    $token = jwtReadToken();
    if ($token === '') {
        jsonResponse(null, false, 'JWT', 'Token ontbreekt.', [], 401, $request);
    }

    $secret = (defined('CONFIG') && isset(CONFIG['app_key'])) ? (string) CONFIG['app_key'] : '';
    $payload = jwtRead($token, $secret);
    if ($payload === null) {
        jsonResponse(null, false, 'JWT', 'Token is ongeldig.', [], 401, $request);
    }

    $due = isset($payload['due']) ? strtotime((string) $payload['due']) : false;
    if ($due === false || $due < time()) {
        jsonResponse(null, false, 'JWT', 'Token is verlopen.', [], 401, $request);
    }
}

// true zodra controller, en eventueel method of table, in de lijst staat.
function jwtMustCheck(string $controller, string $method, string $table): bool
{
    if (!defined('CONFIG') || !isset(CONFIG['jwt_guard']) || !is_array(CONFIG['jwt_guard'])) {
        return false;
    }

    foreach (CONFIG['jwt_guard'] as $rule) {
        if (!is_array($rule) || !isset($rule['controller']) || (string) $rule['controller'] !== $controller) {
            continue;
        }
        if (isset($rule['method']) && strtoupper((string) $rule['method']) !== $method) {
            continue;
        }
        if (isset($rule['table']) && (string) $rule['table'] !== $table) {
            continue;
        }
        return true;
    }

    return false;
}

// POST met _method telt als die methode, net als in de API.
function jwtEffectiveMethod(string $method): string
{
    if ($method !== 'POST') {
        return $method;
    }

    $body = jwtBody();
    $post = [];
    if (isset($body['request']['post']) && is_array($body['request']['post'])) {
        $post = $body['request']['post'];
    } elseif ($body !== []) {
        $post = $body;
    }
    if (!isset($post['_method']) || !is_string($post['_method'])) {
        return $method;
    }

    $override = strtoupper(trim($post['_method']));
    if ($override === 'PUT' || $override === 'PATCH' || $override === 'DELETE') {
        return $override;
    }

    return $method;
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

function jwtReadToken(): string
{
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
    if (isset($_GET['token']) && is_string($_GET['token'])) {
        return $_GET['token'];
    }

    return '';
}

// Maakt een HS256-token. Payload bevat email, name, created_at en due.
function jwtCreate(array $payload, string $secret): string
{
    $header = jwtEncodePart(['alg' => 'HS256', 'typ' => 'JWT']);
    $body = jwtEncodePart($payload);
    $signature = jwtEncodeRaw(hash_hmac('sha256', $header . '.' . $body, $secret, true));

    return $header . '.' . $body . '.' . $signature;
}

// null bij een verkeerde handtekening. De vervaldatum controleert jwtProtect.
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

    $json = jwtDecodePart($parts[1]);
    $payload = json_decode($json, true);
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
