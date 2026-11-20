<?php
// Databaseverbinding, stylesheets en de teksten in de layout.
return [
    'site' => [
        'name' => 'PHP-site',
        'title' => 'php school-site',
        'author' => 'demo-guy',
    ],
    'seo' => [
        'description' => 'Demo-site van de PHP-opleiding met fruitsoorten uit de database.',
        'keywords' => 'php, school, fruit, mysqli',
        'robots' => 'index, follow',
    ],
    'database' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'username' => 'root',
        'password' => '',
        'database' => 'school_db',
        'charset' => 'utf8mb4',
    ],
    'css' => [
        'directory' => basePath('public/css'),
        'pattern' => '*.css',
        'href' => 'css',
    ],
];
