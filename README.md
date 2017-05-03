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

For testing, you will need your <b>Rivio API key</b>  and your <b>secret key</b>. You can get them, from <b><a href="http://dashboard.getrivio.com/dashboard/settings/business" target="_blank">here</a></b>.

### Get Embed Widget

```php
<?php

require_once 'PATH_TO_RIVIO_PHP_SDK/src/Rivio.php';

//Copy credentials from Rivio Dashboard (http://dashboard.getrivio.com/dashboard/settings/business)
$rivio = new Rivio('api_key','secret_key');

//Get the RIVIO script
$rivio_init_script=$rivio->get_init_js();

$rivio_embed_html=$rivio->get_embed_html(
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
$rivio_init_is=$rivio->get_init_js();
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

$result = $rivio->register_postpurchase_email(
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

### For more examples upload the project to your storage and take a look at the examples/index.php.