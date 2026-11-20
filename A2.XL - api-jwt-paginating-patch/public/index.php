<?php
session_start();
require_once dirname(__DIR__) . '/app/lib/helpers/base_path.php';
require_once basePath('app/lib/read_config.php');
require_once basePath('app/lib/db_mysqli/db_connect.php');
require_once basePath('app/lib/db_mysqli/db_query.php');
require_once basePath('app/lib/validationCheck.php');
require_once basePath('app/lib/jwt.php');

$config = readConfig();
$controller = (isset($_GET['controller']) && is_string($_GET['controller'])) ? $_GET['controller'] : '';
$request = [
    'get' => $_GET,
    'post' => [],
    'put' => [],
    'patch' => [],
    'delete' => [],
];

if ($controller === '') {
    $tables = [];
    $databaseConfig = CONFIG['database'];
    $connection = dbConnect();
    // mysqli_set_charset($connection, $databaseConfig['charset']);
    if ($connection !== false) {
        $sql = 'SHOW TABLES';

        // De query wordt hier, in de controller, uitgevoerd.
        $tableRows = dbQuery($connection, $sql);
        if (is_array($tableRows)) {
            foreach ($tableRows as $tableRow) {
                $tables[] = (string) array_values($tableRow)[0];
            }
        }
    }

    $info = $config['api_info'];
    $info['name'] = $config['site']['name'];
    $info['amount'] = (int) $config['api']['amount'];
    $info['tables'] = $tables;

    jsonResponse($info, true, 'API', 'Informatie over de API.', [], 200, $request);
}

jwtProtect($controller);

if ($controller === 'jwt') {
    include basePath('app/controllers/jwt.php');
}

if ($controller !== 'api') {
    jsonResponse(null, false, 'API', 'Controller niet gevonden.', [], 404, $request);
}

include basePath('app/controllers/api.php');
