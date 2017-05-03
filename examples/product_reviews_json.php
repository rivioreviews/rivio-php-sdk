<?php

require_once(__DIR__ . "/bootstrap.php");

$jsonCache = $rivio->get_json_cache();
$jsonCache = json_encode($jsonCache);

echo $jsonCache;