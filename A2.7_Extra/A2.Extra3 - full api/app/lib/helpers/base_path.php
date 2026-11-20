<?php
// Projectmap van A2.5. Een extra pad wordt daarachter geplakt.
function basePath(string $path = ''): string
{
    $base = dirname(__DIR__, 3);
    if ($path === '') {
        return $base;
    }

    return $base . '/' . ltrim($path, '/');
}

// Webpad vanaf de map htdocs, bijvoorbeeld /__2026_PHP/A2.6/A2.6ab.
function basePathFromHtdocs(): string
{
    $full = str_replace('\\', '/', basePath());
    $marker = '/htdocs/';
    $position = strpos($full, $marker);
    if ($position === false) {
        return $full;
    }

    return '/' . substr($full, $position + strlen($marker));
}
