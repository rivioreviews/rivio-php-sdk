<?php

require __DIR__ . '/../src/Rivio.php';

// create config.php file from config.sample.php and set your rivio keys, caching options
if (file_exists(__DIR__ . "/config.php")) {
    require_once(__DIR__ . "/config.php");
    $rivio_api_key = RIVIO_API_KEY;
    $rivio_secret_key = RIVIO_SECRET_KEY;
} else {
    $rivio_api_key = 'your_rivio_api_key';
    $rivio_secret_key = 'your_rivio_secret_key';
}

//set the values for the options array
$options = array(
    "cache" => array(
        "type" => "file_storage",
        "path" => __DIR__ . "/../rivio_cache"
    )
);

$rivio = new Rivio($rivio_api_key, $rivio_secret_key, $options);