<?php
// Zet tekst veilig in HTML.
function escape(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Maakt in één keer elke sleutel en elke waarde schoon.
// Zonder array vult deze functie REQUEST met $_GET en $_POST.
function escapeInput(?array $input = null): array
{
    if ($input === null) {
        if (!defined('REQUEST')) {
            define('REQUEST', [
                'get' => escapeInput($_GET),
                'post' => escapeInput($_POST),
            ]);
        }

        return REQUEST;
    }

    $clean = [];
    foreach ($input as $key => $value) {
        $cleanKey = escape((string) $key);
        if (is_array($value)) {
            $clean[$cleanKey] = escapeInput($value);
        } else {
            $clean[$cleanKey] = escape($value);
        }
    }

    return $clean;
}


