<?php
// Zet een JSON-antwoord op het scherm en stopt daarna.
// status is true of false, http_status is bijvoorbeeld 200.
function jsonResponse(
    mixed $data = null,
    bool $status = true,
    string $descr = '',
    string $message = '',
    array $errors = [],
    int $httpStatus = 200,
    ?array $request = null,
    array $validation = []
): void {
    if (!headers_sent()) {
        http_response_code($httpStatus);
        header('Content-Type: application/json; charset=UTF-8');
    }

    $payload = [
        'data' => $data,
        'count' => recordCount($data),
        'status' => $status,
        'descr' => $descr,
        'message' => $message,
        'errors' => $errors,
        'http_status' => $httpStatus,
        'domain' => escape(requestDomain()),
        'base_path' => escape(responseBasePath()),
    ];
    if ($request !== null) {
        $payload['request'] = $request;
    }
    if ($validation !== []) {
        $payload['validation'] = $validation;
    }

    $payload = maskHiddenValues($payload);
    if (isset($payload['request']) && is_array($payload['request'])) {
        $payload['request'] = escapeRequest($payload['request']);
    }

    $json = json_encode($payload, JSON_UNESCAPED_UNICODE);

    if ($json === false) {
        if (!headers_sent()) {
            http_response_code(500);
        }

        echo '{"data":null,"status":false,"descr":"","message":"JSON coderen is niet gelukt.","errors":[],"http_status":500}';
        exit;
    }

    echo $json;
    exit;
}

// Aantal records. Een lijst telt de rijen. Eén record is 1. Niets gevonden is 0.
function recordCount(mixed $data): int
{
    if (!is_array($data) || $data === []) {
        return 0;
    }
    if (array_is_list($data)) {
        return count($data);
    }

    return 1;
}

// Vervangt geconfigureerde tabelkolommen in data en request door hun placeholder.
// Validatie- en foutteksten blijven staan.
function maskHiddenValues(array $payload): array
{
    $table = null;
    if (isset($payload['request']['get']['table']) && is_string($payload['request']['get']['table'])) {
        $table = $payload['request']['get']['table'];
    }

    $masks = hiddenColumnMasks($table);
    if ($masks === []) {
        return $payload;
    }

    if (array_key_exists('data', $payload)) {
        $payload['data'] = maskHiddenValue($payload['data'], $masks);
    }
    if (isset($payload['request']) && is_array($payload['request'])) {
        $payload['request'] = maskHiddenValue($payload['request'], $masks);
    }

    return $payload;
}

// Kolom => placeholder. Eerst '*', daarna de regels van deze tabel.
function hiddenColumnMasks(?string $table): array
{
    if (!defined('CONFIG') || !isset(CONFIG['api']['hidden']['columns']) || !is_array(CONFIG['api']['hidden']['columns'])) {
        return [];
    }

    $hidden = CONFIG['api']['hidden'];
    $placeholder = (isset($hidden['placeholder']) && is_string($hidden['placeholder'])) ? $hidden['placeholder'] : '********';
    $columns = $hidden['columns'];
    $groups = [];
    if (isset($columns['*']) && is_array($columns['*'])) {
        $groups[] = $columns['*'];
    }
    if ($table !== null && $table !== '*' && isset($columns[$table]) && is_array($columns[$table])) {
        $groups[] = $columns[$table];
    }

    $masks = [];
    foreach ($groups as $group) {
        foreach ($group as $key => $item) {
            if (is_int($key) && is_string($item) && $item !== '') {
                $masks[$item] = $placeholder;
                continue;
            }
            if (is_string($key) && $key !== '' && is_string($item)) {
                $masks[$key] = $item;
            }
        }
    }

    return $masks;
}

// Eén record, een lijst records, of request.post/put/patch/delete.
function maskHiddenValue(mixed $value, array $masks): mixed
{
    if (!is_array($value)) {
        return $value;
    }

    foreach ($value as $key => $item) {
        if (is_string($key) && isset($masks[$key]) && !is_array($item)) {
            $value[$key] = $masks[$key];
            continue;
        }
        if (is_array($item)) {
            $value[$key] = maskHiddenValue($item, $masks);
        }
    }

    return $value;
}

// Maakt request.get/post/put/patch/delete veilig met escape().
function escapeRequest(mixed $value): mixed
{
    if (!is_array($value)) {
        return escape($value);
    }

    $clean = [];
    foreach ($value as $key => $item) {
        $cleanKey = is_string($key) ? escape($key) : $key;
        $clean[$cleanKey] = escapeRequest($item);
    }

    return $clean;
}

// Domein van dit verzoek. HTTP_HOST volgt de server die is aangeroepen.
function requestDomain(): string
{
    $host = '';
    if (isset($_SERVER['HTTP_HOST']) && is_string($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== '') {
        $host = $_SERVER['HTTP_HOST'];
    } elseif (isset($_SERVER['SERVER_NAME']) && is_string($_SERVER['SERVER_NAME'])) {
        $host = $_SERVER['SERVER_NAME'];
    }

    $host = trim($host);
    if (str_starts_with($host, '[')) {
        $end = strpos($host, ']');
        if ($end !== false) {
            return substr($host, 1, $end - 1);
        }
    }

    $colon = strpos($host, ':');
    if ($colon !== false) {
        return substr($host, 0, $colon);
    }

    return $host;
}

// Projectmap uit de config.
function responseBasePath(): string
{
    if (defined('CONFIG') && isset(CONFIG['base_path']) && is_string(CONFIG['base_path'])) {
        return CONFIG['base_path'];
    }
    if (function_exists('basePathFromHtdocs')) {
        return basePathFromHtdocs();
    }

    return '';
}
