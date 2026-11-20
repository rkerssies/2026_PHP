<?php
// Teksten en voorbeelden voor de API-startpagina.
return [
    'api_info' => [
        'description' => 'Basis-API voor de tabellen in de database.',
        'controller' => 'api',
        'examples' => [
            'list' => '?controller=api&table=fruits',
            'page' => '?controller=api&table=fruits&orderby=name&direction=desc&page=2&amount=10',
            'one' => '?controller=api&table=fruits&id=1',
            'first' => '?controller=api&table=fruits&select=first',
            'last' => '?controller=api&table=fruits&select=last',
            'insert' => 'POST ?controller=api&table=fruits met request.post',
            'update' => 'PUT ?controller=api&table=fruits&id=1 met request.put',
            'update_via_post' => 'POST ?controller=api&table=fruits&id=1 met request.post._method=put',
            'patch' => 'PATCH ?controller=api&table=fruits&id=1 met request.patch, of POST ?controller=api&table=fruits&id=1 met request.post._method=patch',
            'patch_via_post' => 'POST ?controller=api&table=fruits&id=1 met request.post._method=patch',
            'login' => 'POST ?controller=jwt met request.post.email en request.post.password',
            'delete' => 'DELETE ?controller=api&table=fruits&id=1',
            'delete_via_post' => 'POST ?controller=api&table=fruits&id=1 met request.post._method=delete',
            'not_found' => [
                ['method' => 'GET', 'url' => '?controller=onbekend', 'http_status' => 404, 'message' => 'Controller niet gevonden.'],
                ['method' => 'GET', 'url' => '?controller=api&table=onbekend', 'http_status' => 404, 'message' => 'Tabel niet gevonden.'],
                ['method' => 'GET', 'url' => '?controller=api&table=fruits&id=999999', 'http_status' => 404, 'message' => 'Record niet gevonden.'],
                ['method' => 'DELETE', 'url' => '?controller=api&table=fruits&id=999999', 'http_status' => 404, 'message' => 'Record niet gevonden.'],
            ],
            'method_not_allowed' => [
                ['method' => 'HEAD', 'url' => '?controller=api&table=users', 'http_status' => 405, 'message' => 'Methode niet toegestaan.'],
            ],
        ],
    ],
];
