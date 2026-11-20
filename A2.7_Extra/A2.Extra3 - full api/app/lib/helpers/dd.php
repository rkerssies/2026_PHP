<?php
// Toont één of meer waarden en stopt daarna, zoals Laravel dd().
function dd(mixed ...$values): void
{
    echo '<pre>';
    foreach ($values as $value) {
        var_dump($value);
    }
    echo '</pre>';
    exit;
}
