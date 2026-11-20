<?php
// Eenvoudige controles voor fruits. required geldt bij toevoegen.
return [
    'name' => [
        'required' => true,
        'type' => 'string',
        'max' => 100,
    ],
    'href' => [
        'required' => true,
        'type' => 'string',
        'max' => 190,
    ],
    'color' => [
        'required' => true,
        'type' => 'string',
        'max' => 80,
    ],
    'sweetness' => [
        'required' => true,
        'type' => 'int',
        'min' => 1,
        'max' => 10,
    ],
    'season' => [
        'required' => true,
        'type' => 'string',
        'max' => 40,
    ],
    'price' => [
        'required' => false,
        'type' => 'decimal',
        'min' => 0,
        'max' => 9999.99,
    ],
    'description' => [
        'required' => false,
        'type' => 'string',
        'max' => 5000,
    ],
    'is_available' => [
        'required' => false,
        'type' => 'bool',
    ],
    'harvested_on' => [
        'required' => false,
        'type' => 'date',
    ],
];
