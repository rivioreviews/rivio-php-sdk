<?php

require_once(__DIR__ . "/bootstrap.php");

$products = array();

// The first product
array_push($products, array(
    "id" => "1492411013331", //$product_id REQUIRED
    "name" => "Samsung Galaxy S6", //$product_name REQUIRED
    "url" => "https://example.com/products/galaxy-s6", //$product_url OPTIONAL
    "image_url" => "https://images.example.com/big/200", //$product_image_url OPTIONAL
    "description" => "This is the product description", //$product_description OPTIONAL
    "barcode" => "1234567890123", //$product_barcode OPTIONAL
    "brand" => "Samsung", //$product_brand OPTIONAL,
    "price" => "499", //$product_price OPTIONAL
));

// The second product
array_push($products, array(
    "id" => "2811046815449", //$product_id REQUIRED
    "name" => "Google Pixel", //$product_name REQUIRED
    "url" => "https://example.com/products/pixel", //$product_url OPTIONAL
    "image_url" => "https://images.example.com/big/300", //$product_image_url OPTIONAL
    "description" => "This is the product description", //$product_description OPTIONAL
    "barcode" => "9876543210987", //$product_barcode OPTIONAL
    "brand" => "Google", //$product_brand OPTIONAL,
    "price" => "799", //$product_price OPTIONAL
));

try {
    $result = $rivio->register_post_purchase_email_multiple_product(
        "1492411013331",//$order_id
        "2015-09-28T09:16:16-04:00",//$ordered_date
        "user@example.com",//$customer_email
        "John",//$customer_first_name
        $products
    );
} catch(Exception $e) {
    $result="Error: ".$e->getMessage();
}

?>
<html>
    <head>
        <title>Register Post-purchase Email with multiple products - Rivio PHP SDK example</title>
    </head>
    <body>
        <h1>Rivio Register Post-purchase Email with multiple products</h1>
        <p>result:</p>
        <pre><?php print_r($result);?></pre>
        Check your Post-purchase email queue on Rivio Dashboard:
        <a href="http://dashboard.getrivio.com/dashboard/email/summary">http://dashboard.getrivio.com/dashboard/email/summary</a>
    </body>
</html>