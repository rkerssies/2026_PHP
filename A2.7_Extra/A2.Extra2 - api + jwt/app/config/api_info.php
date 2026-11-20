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
            'insert' => 'POST ?controller=api&table=fruits met request.post',
            'update' => 'PUT ?controller=api&table=fruits&id=1 met request.put',
            'update_via_post' => 'POST ?controller=api&table=fruits&id=1 met request.post._method=put',
            'patch' => 'PATCH ?controller=api&table=fruits&id=1 met request.patch, of POST ?controller=api&table=fruits&id=1 met request.post._method=patch',
            'patch_via_post' => 'POST ?controller=api&table=fruits&id=1 met request.post._method=patch',
            'login' => 'POST ?controller=jwt met {"email":"admin@school.nl","password":"password"}',
            'token' => '?controller=api&table=fruits&token=PLAK_HIER_HET_TOKEN (nodig bij POST, PUT, PATCH en DELETE)',
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
        // Axios stopt het HTTP-antwoord in .data. Het token van deze API staat daarna in .data.token.
        'axios' => [
            'login' => "const url = 'http://localhost/__2026_PHP/A2.6/A2.6cd/public/index.php';\n\nconst login = await axios.post(url, {\n  email: 'admin@school.nl',\n  password: 'password'\n}, {\n  params: { controller: 'jwt' }\n});\n\nconst token = login.data.data.token;",
            'lezen' => "const lijst = await axios.get(url, {\n  params: { controller: 'api', table: 'fruits' }\n});",
            'schrijven' => "await axios.post(url, {\n  request: {\n    post: {\n      name: 'kiwi',\n      href: 'images/fruits/kiwi.png',\n      color: 'groen',\n      sweetness: 6,\n      season: 'zomer'\n    }\n  }\n}, {\n  params: {\n    controller: 'api',\n    table: 'fruits',\n    token: token\n  }\n});",
            'header' => "headers: { Authorization: 'Bearer ' + token }",
        ],
    ],
];
