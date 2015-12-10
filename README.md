Rivio PHP SDK
=============

The Rivio PHP SDK provides integration access to the Rivio API.

## Dependencies

PHP version >= 5.2.0 is required.

The following PHP extensions are required:

* curl

## Install and configure

###Get started with our PHP SDK by hitting the download link below.

###Or use composer

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

## Quick Start Example

```php
<?php

require_once 'PATH_TO_RIVIO_PHP_SDK/src/Rivio.php';

//Copy credentials from Rivio Dashboard (http://dashboard.reev.io/dashboard/settings/business)
$rivio = new Rivio('api_key','secret_key','ID');


$result=$rivio->register_postpurchase_email();

var_dump($result);
```

## License

See the LICENSE file.

