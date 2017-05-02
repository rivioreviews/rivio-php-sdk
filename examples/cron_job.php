<?php
require __DIR__ . '/src/Rivio.php';

//create config.php file from config.sample.php and set your rivio keys
if (file_exists(__DIR__ . "/config.php")) {
    require_once(__DIR__ . "/config.php");
    $rivio_api_key = RIVIO_API_KEY;
    $rivio_secret_key = RIVIO_SECRET_KEY;
    $rivio_cache_type = RIVIO_CACHE_TYPE;
    $rivio_cache_path = RIVIO_CACHE_PATH;
} else {
    $rivio_api_key = 'your_rivio_api_key';
    $rivio_secret_key = 'your_rivio_secret_key';
    $rivio_cache_type = 'file_storage';
    $rivio_cache_path = __DIR__ . '/cache';
}

//set the values for the options array
$options = array(
    "cache" => array(
        "type" => $rivio_cache_type,
        "path" => $rivio_cache_path
    )

);

$rivio = new Rivio($rivio_api_key, $rivio_secret_key, $options);

$rivio->get_json_cache();

?>