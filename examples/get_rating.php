<?php
require __DIR__ . '/../src/Rivio.php';

//create config.php file from config.sample.php and set your rivio keys
if(file_exists(__DIR__."/config.php")){
    require_once(__DIR__."/config.php");
    $rivio_api_key=RIVIO_API_KEY;
    $rivio_secret_key=RIVIO_SECRET_KEY;
}else{
    $rivio_api_key='your_rivio_api_key';
    $rivio_secret_key='your_rivio_secret_key';
}

//Copy credentials from Rivio Dashboard (http://dashboard.getrivio.com/dashboard/settings/business)
$rivio = new Rivio($rivio_api_key,$rivio_secret_key);

?>

<html>
    <head>
        <title>Product rating module - Rivio PHP SDK example</title>
    </head>
    <body>
        <h1>
            Product rating stars example
        </h1>

        <div>
            <h2>
                Smartphone6
            </h2>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut lectus purus. Praesent dapibus nisl vitae aliquam egestas. Sed id nibh ut nunc dapibus efficitur vitae et ligula.
            </p>
            <p>
                Rating:
                <?php echo $rivio->get_rating('3409787460');?> <!-- Get product rating stars with the id of the product-->
            </p>
        </div>

        <div>
            <h2>
                Smarphone6s
            </h2>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut lectus purus. Praesent dapibus nisl vitae aliquam egestas. Sed id nibh ut nunc dapibus efficitur vitae et ligula.
            </p>
            <p>
                Rating:
                <?php echo $rivio->get_rating('3409788036');?> <!-- Get product rating stars with the id of the product-->
            </p>
        </div>

        <?php echo $rivio_init_script;?>
    </body>
</html>