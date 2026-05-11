<?php
require_once('../src/helpers.php');

$cursor = \accessCore\getPGConn();
$result = pg_query($cursor, "SELECT * FROM plushies.getPlushie()") or die('Query failed: ' . pg_last_error());
$entries = pg_fetch_all($result);
pg_free_result($result);
$result = pg_query($cursor, "SELECT * FROM plushies.getFilterValues()") or die('Query failed: ' . pg_last_error());
$filterValues = pg_fetch_result($result, 0, 0);
pg_free_result($result);
pg_close($cursor);
print_r($entries);
print_r($filterValues);
