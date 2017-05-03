<?php

require_once(__DIR__ . "/bootstrap.php");

//Get the RIVIO script
$rivio_init_script=$rivio->get_init_js();

$rivio_embed_html=$rivio->product_reviews_widget(
    "1492411012",//$product_id
    "Samsung Galaxy S6",//$product_name
    "https://example.com/products/galaxy-s6",//$product_url
    "https://images.example.com/big/200",//$product_image_url
    "This is the product description",//$product_description
    "1234567890123",//$product_barcode
    "Mobile phone",//$product_category
    "Samsung",//$product_brand
    "499",//$product_price,
    "en"//$lang
);
?>
<html>
    <head>
        <title>Embed module - Rivio PHP SDK example</title>
    </head>
    <body>
        <h1>Rivio Embed Module</h1>
        <?php echo $rivio_embed_html;?>
        <?php echo $rivio_init_script;?>
    </body>
</html>
