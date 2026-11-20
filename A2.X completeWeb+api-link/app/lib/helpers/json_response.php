<?php
// Zet een JSON-antwoord op het scherm en stopt daarna.
// status is true of false, http_status is bijvoorbeeld 200.
function jsonResponse(
    mixed $data = null,
    bool $status = true,
    string $descr = '',
    string $message = '',
    array $errors = [],
    int $httpStatus = 200
): void {
    if (!headers_sent()) {
        http_response_code($httpStatus);
        header('Content-Type: application/json; charset=UTF-8');
    }

    $json = json_encode(
        [
            'data' => $data,
            'status' => $status,
            'descr' => $descr,
            'message' => $message,
            'errors' => $errors,
            'http_status' => $httpStatus,
        ],
        JSON_UNESCAPED_UNICODE
    );

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
