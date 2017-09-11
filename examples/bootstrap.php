<?php

require __DIR__ . '/../src/Rivio.php';

$rivio_api_key = 'your_rivio_api_key';
$rivio_secret_key = 'your_rivio_secret_key';

//set the values for the options array
$options = array(
    "cache" => array(
        "type" => "file_storage",
        "path" => __DIR__ . "/../rivio_cache"
    )
);

$rivio = new Rivio($rivio_api_key, $rivio_secret_key, $options);