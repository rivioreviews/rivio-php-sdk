<?php

require_once(__DIR__ . "/bootstrap.php");

$rivio_reviews_json = $rivio->product_reviews_json('1492411012');
$rivio_reviews_json = json_encode($rivio_reviews_json);

echo $rivio_reviews_json;