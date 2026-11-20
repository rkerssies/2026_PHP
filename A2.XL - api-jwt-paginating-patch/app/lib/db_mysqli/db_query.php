<?php
// Voert één SQL-query uit op de verbinding uit dbConnect().
// SELECT (en andere result-queries) geven een array van rijen terug.
// INSERT, UPDATE en DELETE geven true of false terug.
// insert_id, affected_rows en error staan daarna in dbQueryInfo().

function dbQuery($connection, string $sql)
{
    $result = mysqli_query($connection, $sql);
    dbQueryInfo(
        mysqli_insert_id($connection),
        mysqli_affected_rows($connection),
        mysqli_error($connection)
    );

    if ($result === false) {
        return false;
    }

    if ($result === true) {
        return true;
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_free_result($result);

    return $rows;
}



function dbQueryInfo(?int $insertId = null, ?int $affectedRows = null, ?string $error = null): array
{
    static $info = [
        'insert_id' => 0,
        'affected_rows' => 0,
        'error' => '',
    ];

    if ($insertId !== null) {
        $info = [
            'insert_id' => $insertId,
            'affected_rows' => $affectedRows ?? 0,
            'error' => $error ?? '',
        ];
    }

    return $info;
}
