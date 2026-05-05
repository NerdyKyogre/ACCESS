<?php
namespace accessCore;

function getPGConn(bool $ro = True)
{
    $config = parse_ini_file('../config.ini');
    $dbUser = $config['DB_USER'];
    $dbPass = $config['DB_PASSWORD'];
    $dbPort = $config['DB_PORT'] ?? 5432;
    $dbName = $config['DB_NAME'];

    $conn = pg_connect("host=localhost:$dbPort dbname=$dbName user=$dbUser password=$dbPass") or die('Could not connect: ' . pg_last_error());
    if($ro)
        pg_query($conn, 'SET SESSION CHARACTERISTICS AS TRANSACTION READ ONLY;');
    return $conn;
}
