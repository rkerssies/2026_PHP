<?php
// Teksten en voorbeelden voor de API-startpagina.
return [
    'api_info' => [
        'description' => 'Basis-API voor de tabellen in de database.',
        'controller' => 'api',
        'examples' => [
            'list' => '?controller=api&table=fruits',
            'one' => '?controller=api&table=fruits&id=1',
            'first' => '?controller=api&table=fruits&select=first',
            'last' => '?controller=api&table=fruits&select=last',
            'insert' => 'POST ?controller=api&table=fruits met request.post',
            'update' => 'PUT ?controller=api&table=fruits&id=1 met request.put',
            'delete' => 'DELETE ?controller=api&table=fruits&id=1',
        ],
    ],
];
