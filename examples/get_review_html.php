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
$options = [
    "cache" => [
        "type" => "file",
        "path" => __DIR__ . "/rivio-cache"
    ],
];
$rivio = new Rivio($rivio_api_key,$rivio_secret_key,$options);

// Init cache, get json cache, save it in unique files for every products
$rivio->get_json_cache();

//Get the RIVIO script
$rivio_init_script=$rivio->get_init_js();

// If has cache, load it. If not, then won't load anything.
$rivio_embed_html=$rivio->get_embed_widget(
    "1492411012",//$product_id
    "Samsung Galaxy S6",//$product_name
    "https://example.com/products/galaxy-s6",//$product_url
    "https://images.example.com/big/200",//$product_image_url
    "This is the product description",//$product_description
    "1234567890123",//$product_barcode
    "Mobile phone",//$product_category
    "Samsung",//$product_brand
    "499",//$product_price
    "en",//$lang
    true//$server_side_rendering
);
?>
<html>
<head>
    <title>Embed module - Rivio PHP SDK example</title>
    <link rel="stylesheet" href="./../src/assets/review.css">
</head>
<body>
<h1>Rivio Embed Module</h1>
<?php echo $rivio_embed_html;?>
<?php echo $rivio_init_script;?>
</body>
</html>
