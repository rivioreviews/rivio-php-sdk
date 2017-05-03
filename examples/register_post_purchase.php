<?php

require_once(__DIR__ . "/bootstrap.php");

//Minimal parameters list
/*
$result=$rivio->register_post_purchase_email(
    "1492411013333333s",//$order_id
    "2015-09-28T09:16:16-04:00",//$ordered_date
    "user@example.com",//$customer_email
    "John",//$customer_first_name
    "1492411012",//$product_id
    "Samsung Galaxy S6"//$product_name
);*/

//Full parameters list
try {
    $result = $rivio->register_post_purchase_email(
        "1492411013331",//$order_id
        "2015-09-28T09:16:16-04:00",//$ordered_date
        "user@example.com",//$customer_email
        "John",//$customer_first_name
        "1492411012",//$product_id
        "Samsung Galaxy S6",//$product_name
        "https://example.com/products/galaxy-s6",//$product_url
        "https://images.example.com/big/200",//$product_image_url
        "This is the product description",//$product_description
        "1234567890123",//$product_barcode
        "Mobile phone",//$product_category
        "Samsung",//$product_brand
        "499"//$product_price
    );
} catch(Exception $e) {
    $result="Error: ".$e->getMessage();
}

?>
<html>
    <head>
        <title>Register Post-purchase Email - Rivio PHP SDK example</title>
    </head>
    <body>
        <h1>Rivio Register Post-purchase Email</h1>
        <p>result:</p>
        <pre><?php print_r($result);?></pre>
        Check your Post-purchase email queue on Rivio Dashboard:
        <a href="http://dashboard.getrivio.com/dashboard/email/summary">http://dashboard.getrivio.com/dashboard/email/summary</a>
    </body>
</html>