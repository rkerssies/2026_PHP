<?php
// Projectmap (vanuit public/: één map omhoog). Een extra pad wordt daarachter geplakt.
function basePath(string $path = ''): string
{
    $base = './..';
    if ($path === '') {
        return $base;
    }

    return $base . '/' . ltrim($path, '/');
}
