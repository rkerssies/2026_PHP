<?php
// Zet tekst veilig in HTML.
function escape(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
