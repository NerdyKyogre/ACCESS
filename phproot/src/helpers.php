<?php
namespace accessCore;
define(__NAMESPACE__ .'\\CONFIG', parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/../config.ini'));

/**
 * Gets a connection to the postgres database using the parameters defined in config.ini
 * @param bool $ro True if read-only, False for write access. True by default.
 * @return \PgSql\Connection The requested connection. Dies with error on failure.
 */
function getPGConn(bool $ro = True)
{
    $dbUser = CONFIG['DB_USER'];
    $dbPass = CONFIG['DB_PASSWORD'];
    $dbPort = CONFIG['DB_PORT'] ?? 5432;
    $dbName = CONFIG['DB_NAME'];

    $conn = pg_connect("host=localhost port=$dbPort dbname=$dbName user=$dbUser password=$dbPass") or die('Could not connect: ' . pg_last_error());
    if($ro)
        pg_query($conn, 'SET SESSION CHARACTERISTICS AS TRANSACTION READ ONLY;');
    return $conn;
}
