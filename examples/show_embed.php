<?php
require __DIR__ . '/../src/Rivio.php';

if(file_exists(__DIR__."/config.php")){
    require_once(__DIR__."/config.php");
}else{
    require_once(__DIR__."/config.sample.php");
}

//Copy credentials from Rivio Dashboard (http://dashboard.reev.io/dashboard/settings/business)
$rivio = new Rivio('your_rivio_api_key','your_rivio_secret_key');

$rivio_embed_html=$rivio->get_embed_html();
?>
<html>
    <head>
        <title>Embed module - Rivio PHP SDK example</title>
    </head>
    <body>
        <h1>Rivio Embed Module:</h1>
        <?php echo $rivio_embed_html;?>
    </body>
</html>
