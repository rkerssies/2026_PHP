<?php
// Bouwt een procedurele mysqli-verbinding met de database-gegevens uit CONFIG.
function dbConnect()
{
    require_once basePath('app/lib/read_config.php');
    readConfig();

    mysqli_report(MYSQLI_REPORT_OFF);

    $connection = mysqli_connect(
        CONFIG['database']['host'],
        CONFIG['database']['username'],
        CONFIG['database']['password'],
        CONFIG['database']['database'],
        CONFIG['database']['port']
    );

    if ($connection === false) {
        return false;
    }

    if (isset(CONFIG['database']['charset']) && is_string(CONFIG['database']['charset'])) {
        mysqli_set_charset($connection, CONFIG['database']['charset']);
    }

    return $connection;
}
