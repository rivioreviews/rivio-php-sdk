<?php

require_once(__DIR__ . "/bootstrap.php");

//Get the RIVIO script
$rivio_init_script=$rivio->get_init_js();

?>

<html>
    <head>
        <title>Product rating module - Rivio PHP SDK example</title>

        <?php echo $rivio_init_script;?>
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
            <?php echo $rivio->stars_widget('1492411012');?> <!-- Get product rating stars with the id of the product-->
        </div>

        <div>
            <h2>
                Smarphone6s
            </h2>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut lectus purus. Praesent dapibus nisl vitae aliquam egestas. Sed id nibh ut nunc dapibus efficitur vitae et ligula.
            </p>
            <?php echo $rivio->stars_widget('3409788036');?> <!-- Get product rating stars with the id of the product-->
        </div>
    </body>
</html>