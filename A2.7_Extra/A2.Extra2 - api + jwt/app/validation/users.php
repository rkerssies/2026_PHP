<?php
// Eenvoudige controles voor users. required geldt bij toevoegen.
return [
    'name' => [
        'required' => true,
        'type' => 'string',
        'max' => 100,
    ],
    'email' => [
        'required' => true,
        'type' => 'email',
        'max' => 190,
    ],
    'password' => [
        'required' => true,
        'type' => 'string',
        'max' => 40,
    ],
    'favorite_fruit_id' => [
        'required' => false,
        'type' => 'int',
        'min' => 1,
    ],
];
