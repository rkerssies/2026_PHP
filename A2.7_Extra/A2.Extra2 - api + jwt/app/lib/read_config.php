<?php
// Leest de php-bestanden in app/config. De sleutels uit die bestanden komen in CONFIG.
function readConfig(): array
{
    require_once basePath('app/lib/helpers/dd.php');
    require_once basePath('app/lib/helpers/escape.php');
    require_once basePath('app/lib/helpers/json_response.php');

    if (defined('CONFIG')) {
        return CONFIG;
    }

    $config = [];
    $files = glob(basePath('app/config/*.php'));
    if ($files !== false) {
        foreach ($files as $file) {
            $values = require $file;
            if (is_array($values)) {
                $config = array_merge($config, $values);
            }
        }
    }

    define('CONFIG', $config);
    return CONFIG;
}
