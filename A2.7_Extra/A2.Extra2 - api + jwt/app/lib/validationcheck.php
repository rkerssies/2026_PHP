<?php
// Controleert verstuurde velden met de regels uit app/validation/<tabel>.php.
// Bij toevoegen zijn verplichte velden verplicht. Bij wijzigen alleen de meegestuurde velden.
function validationCheck(string $table, array $fields, bool $requireMissing): array
{
    $directory = realpath(basePath('app/validation'));
    $file = realpath(basePath('app/validation/' . $table . '.php'));
    if ($directory === false || $file === false || !str_starts_with($file, $directory . DIRECTORY_SEPARATOR)) {
        return [];
    }

    $rules = require $file;
    if (!is_array($rules)) {
        return [];
    }

    $validation = [];
    foreach ($rules as $column => $rule) {
        if (!is_string($column) || !is_array($rule)) {
            continue;
        }

        $required = isset($rule['required']) && $rule['required'] === true;
        if (!array_key_exists($column, $fields)) {
            if ($requireMissing && $required) {
                $validation[$column] = $column . ' is verplicht.';
            }
            continue;
        }

        $message = validationCheckValue($column, $fields[$column], $rule);
        if ($message !== '') {
            $validation[$column] = $message;
        }
    }

    return $validation;
}

// Eén veld controleren. Lege tekst is geen fout als het veld niet verplicht is.
function validationCheckValue(string $column, mixed $value, array $rule): string
{
    $required = isset($rule['required']) && $rule['required'] === true;
    $type = (isset($rule['type']) && is_string($rule['type'])) ? $rule['type'] : 'string';

    if ($value === null) {
        return $required ? $column . ' is verplicht.' : '';
    }

    if ($type === 'string' || $type === 'email') {
        if (!is_string($value) && !is_numeric($value)) {
            return $column . ' moet een tekst zijn.';
        }
        $text = trim((string) $value);
        if ($text === '') {
            return $required ? $column . ' is verplicht.' : '';
        }
        if (isset($rule['max']) && mb_strlen($text) > (int) $rule['max']) {
            return $column . ' is maximaal ' . (int) $rule['max'] . ' tekens.';
        }
        if ($type === 'email' && preg_match('/\A[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}\z/', $text) !== 1) {
            return $column . ' is geen geldig e-mailadres.';
        }
        if (isset($rule['pattern']) && is_string($rule['pattern']) && $rule['pattern'] !== '' && preg_match($rule['pattern'], $text) !== 1) {
            return $column . ' heeft niet de juiste vorm.';
        }
        return '';
    }

    if ($type === 'int') {
        $number = validationWholeNumber($value);
        if ($number === null) {
            return $column . ' moet een geheel getal zijn.';
        }
        if (isset($rule['min']) && $number < (int) $rule['min']) {
            return $column . ' is minimaal ' . (int) $rule['min'] . '.';
        }
        if (isset($rule['max']) && $number > (int) $rule['max']) {
            return $column . ' is maximaal ' . (int) $rule['max'] . '.';
        }
        return '';
    }

    if ($type === 'decimal') {
        if (!is_int($value) && !is_float($value) && !(is_string($value) && is_numeric($value))) {
            return $column . ' moet een getal zijn.';
        }
        $number = (float) $value;
        if (isset($rule['min']) && $number < (float) $rule['min']) {
            return $column . ' is minimaal ' . $rule['min'] . '.';
        }
        if (isset($rule['max']) && $number > (float) $rule['max']) {
            return $column . ' is maximaal ' . $rule['max'] . '.';
        }
        return '';
    }

    if ($type === 'bool') {
        if ($value === true || $value === false || $value === 0 || $value === 1 || $value === '0' || $value === '1') {
            return '';
        }
        return $column . ' is 0 of 1.';
    }

    if ($type === 'date') {
        if (!is_string($value) || preg_match('/\A(\d{4})-(\d{2})-(\d{2})\z/', $value, $parts) !== 1) {
            return $column . ' is een datum als JJJJ-MM-DD.';
        }
        if (!checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1])) {
            return $column . ' is een datum als JJJJ-MM-DD.';
        }
        return '';
    }

    return '';
}

// Geheel getal uit JSON of uit een tekstveld. Null als het geen geheel getal is.
function validationWholeNumber(mixed $value): ?int
{
    if (is_int($value)) {
        return $value;
    }
    if (is_string($value) && preg_match('/\A-?\d+\z/', $value) === 1) {
        return (int) $value;
    }

    return null;
}
