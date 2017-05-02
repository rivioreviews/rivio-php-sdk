<?php

require_once(__DIR__ . "/bootstrap.php");

// Products, getting review(s) in the last 24 hours
$date = date("Y-m-d_H:i:s", strtotime('-1 day'));

$rivio->execute_cron($date);

?>