<?php
// Endpoints die een JWT-token nodig hebben.
// Staat een verzoek hier niet in, dan wordt de url gewoon uitgevoerd.
// controller is verplicht. method en table zijn optioneel.
return [
    'jwt_guard' => [
        ['controller' => 'api', 'method' => 'POST'],
        ['controller' => 'api', 'method' => 'PUT'],
        ['controller' => 'api', 'method' => 'PATCH'],
        ['controller' => 'api', 'method' => 'DELETE'],
    ],
];
