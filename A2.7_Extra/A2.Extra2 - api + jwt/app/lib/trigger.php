<?php
function triggerController(): string
{
    $controller = $_GET['controller'] ?? 'home';
    // Alleen eenvoudige controllernamen: geen paden uit de URL includen.
    if (!is_string($controller) || !preg_match('/\A[a-z][a-z0-9_]*\z/', $controller)) {
        http_response_code(404);
        return '<h2>404 — Pagina niet gevonden</h2>';
    }

    $path = basePath('app/controllers/' . $controller . '.php');
    if (!is_file($path)) {
        http_response_code(404);
        return '<h2>404 — Pagina niet gevonden</h2>';
    }

    return include $path;
}
