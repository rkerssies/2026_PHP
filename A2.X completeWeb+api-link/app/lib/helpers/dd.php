<?php
// Toont één of meer waarden en stopt daarna, zoals Laravel dd().
function dd(mixed ...$values): void
{

    if (is_array($values)) {
        echo '<pre>';
        foreach ($values as $value) {
            var_dump($value);
        }
        echo '</pre>';
    }
    else {
        var_dump( (string)$values);
    }
    exit;
}
