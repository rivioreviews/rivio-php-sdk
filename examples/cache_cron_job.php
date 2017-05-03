<?php

require_once(__DIR__ . "/bootstrap.php");

$rivio->execute_cron();

?>
<html>
<head>
    <title>Server side review caching - Rivio PHP SDK example</title>
</head>
<body>
<h1>Server side review caching</h1>
Your reviews should be cached in the folder, set in the array <b>$options</b> at <b>examples/bootstrap.php</b>.<br><br>
An example for the $options array:
<pre style="background: #eeeeee; padding: 8px;">
    $options = array(
        "cache" => array(
            "type" => "file_storage",
            "path" => __DIR__ . "/../rivio_cache"
        )
    );
</pre>
</body>
</html>
