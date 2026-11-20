<?php
// Databaseverbinding, stylesheets en de teksten in de layout.
return [
    'app_key' => 'school-jwt-app-key-a26cd-2026',
    'jwt_due' => 3600,
    'base_path' => basePathFromHtdocs(),
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
    'api' => [
        'amount' => 10,
        // Kolommen die in de JSON worden vervangen. '*' geldt voor elke tabel.
        // Extra tabel of kolom: voeg een sleutel toe. Eigen tekst: 'email' => '[verborgen]'.
        'hidden' => [
            'placeholder' => '********',
            'columns' => [
                '*' => ['password'],
            ],
        ],
    ],
    'database' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'username' => 'root',
        'password' => '',
        'database' => 'school_db',
        'charset' => 'utf8mb4',
    ],

];
