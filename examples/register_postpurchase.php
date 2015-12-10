<?php
require __DIR__ . '/../src/Rivio.php';

if(file_exists(__DIR__."/config.php")){
    require_once(__DIR__."/config.php");
}else{
    require_once(__DIR__."/config.sample.php");
}

//Copy credentials from Rivio Dashboard (http://dashboard.reev.io/dashboard/settings/business)
$rivio = new Rivio('your_rivio_api_key','your_rivio_secret_key');

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
$result=$rivio->register_postpurchase_email(
    "149241101333",//$order_id
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


?>
<html>
    <head>
        <title>register postpurchase email - Rivio PHP SDK example</title>
    </head>
    <body>
        Result:
        <pre>
            <?php print_r($result);?>
        </pre>
        Check your pospurcahse email queue oRivio Dashboard:
        <a href="http://dashboard.reev.io/dashboard/email/summary">http://dashboard.reev.io/dashboard/email/summary</a>
    </body>
</html>