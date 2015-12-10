<?php
require __DIR__ . '/../src/Rivio.php';

$rivio_api_key='your_rivio_api_key';
$rivio_secret_key='your_rivio_secret_key';

//create config.php file from config.sample.php and set your rivio keys
if(file_exists(__DIR__."/config.php")){
    require_once(__DIR__."/config.php");
    $rivio_api_key=RIVIO_API_KEY;
    $rivio_secret_key=RIVIO_SECRET_KEY;
}

//Copy credentials from Rivio Dashboard (http://dashboard.reev.io/dashboard/settings/business)
$rivio = new Rivio($rivio_api_key,$rivio_secret_key);

//Minimal parameters list
/*
$result=$rivio->register_postpurchase_email(
    "1492411013333333s",//$order_id
    "2015-09-28T09:16:16-04:00",//$ordered_date
    "user@example.com",//$customer_email
    "John",//$customer_first_name
    "1492411012",//$product_id
    "Samsung Galaxy S6"//$product_name
);*/

//Full parameters list
try {
    $result = $rivio->register_postpurchase_email(
        "1492411013331",//$order_id
        "2015-09-28T09:16:16-04:00",//$ordered_date
        "user@example.com",//$customer_email
        "John",//$customer_first_name
        "1492411012",//$product_id
        "Samsung Galaxy S6",//$product_name
        "This is the product description",//$product_description
        "https://example.com/products/galaxy-s6",//$product_url
        "https://images.example.com/big/200",//$product_image_url
        "1234567890123",//$product_barcode
        "Mobile phone",//$product_category
        "Samsung",//$product_brand
        "499"//$product_price
    );
}catch(Exception $e){
    $result="Error: ".$e->getMessage();
}


?>
<html>
    <head>
        <title>register postpurchase email - Rivio PHP SDK example</title>
    </head>
    <body>
        <h1>Rivio register_postpurchase_email result:</h1>
        <pre><?php print_r($result);?></pre>
        Check your pospurcahse email queue Rivio Dashboard:
        <a href="http://dashboard.reev.io/dashboard/email/summary">http://dashboard.reev.io/dashboard/email/summary</a>
    </body>
</html>