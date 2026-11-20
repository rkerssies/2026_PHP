<?php
// Controles voor het inloggen. Alleen email en password.
return [
    'email' => [
        'required' => true,
        'type' => 'email',
        'max' => 190,
        'pattern' => '/\A[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}\z/',
    ],
    'password' => [
        'required' => true,
        'type' => 'string',
        'max' => 40,
        // Niet actief. Het oefenwachtwoord is "password" en dat moet blijven werken.
        // Kleine letter, hoofdletter, cijfer en een speciaal teken.
        // 'pattern' => '/\A(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+\z/',
    ],
];
