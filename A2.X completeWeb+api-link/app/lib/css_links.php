<?php
// Leest alle .css-bestanden in public/css en geeft per bestand een link-tag terug.
function cssLinks(): string
{
    $cssConfig = CONFIG['css'];
    $files = glob($cssConfig['directory'] . '/' . $cssConfig['pattern']);
    
    if ($files === false) {
        return '';
    }

    $html = '';
    foreach ($files as $file) {
        $href = $cssConfig['href'] . '/' . basename($file);
        $html .= '<link rel="stylesheet" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }

    return $html;
}
