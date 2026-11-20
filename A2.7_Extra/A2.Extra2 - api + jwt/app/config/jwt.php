<?php
// Welke aanroepen een token nodig hebben.
// De sleutel is de controller. De lijst is de methode.
// GET staat er niet in, dus lezen mag zonder token.
return [
    'jwt_guard' => [
        'api' => ['POST', 'PUT', 'PATCH', 'DELETE'],
    ],
];
