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
$rivio = new Rivio($rivio_api_key, $rivio_secret_key);

$rating_1 = $rivio->get_rating('3409787460');
$rating_2 = $rivio->get_rating('3409787460');

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
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut lectus purus. Praesent dapibus nisl vitae
        aliquam egestas. Sed id nibh ut nunc dapibus efficitur vitae et ligula.
    </p>

    <p>
    <ul>
        <li>Rating: <?php echo $rating_1["avg"]; ?></li>
        <li>Count: <?php echo $rating_1["count"]; ?></li>
    </ul>
    </p>
</div>

<div>
    <h2>
        Smarphone6s
    </h2>

    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut lectus purus. Praesent dapibus nisl vitae
        aliquam egestas. Sed id nibh ut nunc dapibus efficitur vitae et ligula.
    </p>

    <p>
    <ul>
        <li>Rating: <?php echo $rating_2["avg"]; ?></li>
        <li>Count: <?php echo $rating_2["count"]; ?></li>
    </ul>
    </p>
</div>

</body>
</html>