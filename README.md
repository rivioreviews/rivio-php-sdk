Rivio PHP SDK
=============

The Rivio PHP SDK provides integration access to the Rivio API.

## Dependencies

PHP version >= 5.2.0 is required.

## Install and configure

### Get started with our PHP SDK by hitting the download link below.

[Download](https://github.com/rivioreviews/rivio-php-sdk/archive/master.zip)

### Or use composer

[Composer](http://getcomposer.org/doc/01-basic-usage.md) is a package manager for PHP. In the `composer.json` file in your project add:

```javascript
{
  "require" : {
    "rivio/rivio-php-sdk": "*"
  }
}
```

And then run:

    php composer.phar install

## Quick Start Examples

For testing, you will need your <b>Rivio API key</b>  and your <b>Secret key</b>. You can get them, from <b><a href="http://dashboard.getrivio.com/dashboard/settings/business" target="_blank">here</a></b>.

### Product Reviews Widget

```php
<?php

require_once 'PATH_TO_RIVIO_PHP_SDK/src/Rivio.php';

//Copy credentials from Rivio Dashboard (http://dashboard.getrivio.com/dashboard/settings/business)
$rivio = new Rivio('api_key','secret_key');

//Get the RIVIO script
$rivio_init_script=$rivio->get_init_js();

$rivio_embed_html=$rivio->product_reviews_widget(
    "1492411012", //$product_id REQUIRED
    "Samsung Galaxy S6", //$product_name REQUIRED
    "https://example.com/products/galaxy-s6", //$product_url OPTIONAL
    "https://images.example.com/big/200", //$product_image_url OPTIONAL
    "This is the product description", //$product_description OPTIONAL
    "1234567890123", //$product_barcode OPTIONAL
    "Mobile phone", //$product_category OPTIONAL
    "Samsung", //$product_brand OPTIONAL
    "499", //$product_price OPTIONAL
    $reviews_html //$reviews_html OPTIONAL (from product_reviews_html)
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
```

### Register Post-purchase Email

After a purchase in your store, this code will send a "Post purchase email" to the buyer to write a review about it.<br>You can also configure this email sending <b><a href="https://dashboard.reev.io/dashboard/email/settings" target="_blank">here</a></b>.

```php
<?php

require_once 'PATH_TO_RIVIO_PHP_SDK/src/Rivio.php';

//Copy credentials from Rivio Dashboard (http://dashboard.getrivio.com/dashboard/settings/business)
$rivio = new Rivio('api_key','secret_key');

$result = $rivio->register_post_purchase_email(
    "1492411013331", //$order_id REQUIRED
    "2015-09-28T09:16:16-04:00", //$ordered_date REQUIRED
    "user@example.com", //$customer_email REQUIRED
    "John", //$customer_first_name REQUIRED
    "1492411012", //$product_id REQUIRED
    "Samsung Galaxy S6", //$product_name REQUIRED
    "https://example.com/products/galaxy-s6", //$product_url OPTIONAL
    "https://images.example.com/big/200", //$product_image_url OPTIONAL
    "This is the product description", //$product_description OPTIONAL
    "1234567890123", //$product_barcode OPTIONAL
    "Mobile phone", //$product_category OPTIONAL
    "Samsung", //$product_brand OPTIONAL
    "499" //$product_price OPTIONAL
);

?>
<html>
    <head>
        <title>Register Postpurchase Email - Rivio PHP SDK example</title>
    </head>
    <body>
        <h1>Rivio Register Postpurchase Email</h1>
        <p>result:</p>
        <pre><?php print_r($result);?></pre>
        Check your postpurchase email queue on 
        <a href="http://dashboard.getrivio.com/dashboard/email/summary" target="_blank">Rivio Dashboard</a>.
        If the "Email status" is "Pending" then the test was successful.
    </body>
</html>
```

You can add multiple products, using the example below. This way, Rivio will choose the most expensive product from the order. If price is not available for the products, it will choose the first product. After that Rivio is going to send the PostPurchase email about that product.

```php
<?php

require_once 'PATH_TO_RIVIO_PHP_SDK/src/Rivio.php';

//Copy credentials from Rivio Dashboard (http://dashboard.getrivio.com/dashboard/settings/business)
$rivio = new Rivio('api_key','secret_key');

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

$result = $rivio->register_post_purchase_email_multiple_product(
    "1492411013331", //$order_id REQUIRED
    "2015-09-28T09:16:16-04:00", //$ordered_date REQUIRED
    "user@example.com", //$customer_email REQUIRED
    "John", //$customer_first_name REQUIRED
    $products
);

?>
<html>
    <head>
        <title>Register Postpurchase Email with muliple products - Rivio PHP SDK example</title>
    </head>
    <body>
        <h1>Rivio Register Postpurchase Email with muliple products</h1>
        <p>result:</p>
        <pre><?php print_r($result);?></pre>
        Check your postpurchase email queue on 
        <a href="http://dashboard.getrivio.com/dashboard/email/summary" target="_blank">Rivio Dashboard</a>.
        If the "Email status" is "Pending" then the test was successful.
    </body>
</html>
```


### For more examples upload the project to your storage and take a look at the examples/index.php.