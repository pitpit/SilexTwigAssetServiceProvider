SmartRock Silex Twig Service Provider
=========================================

Enhanced Twig service provider that includes the twig "asset" function from Symfony, missing by default in [Silex][1].

Installation
------------

Use [Composer][2].

### Install Composer

In your command line, run:

``` bash
$ curl -s http://getcomposer.org/installer | php
```

Or [download composer.phar][3] into the project root.

### Add FacebookServiceProvider to your composer.json

    "require": {
        "smartrock/silex-twig-service-provider": "dev-master"
    }

Registering
-----------

Replace

```php
$app->register(new Silex\Provider\TwigServiceProvider());
```

with

```php
$app->register(new SmartRock\Provider\TwigAssetServiceProvider());
```

Usage
--------
Now you have access to `asset('path/to/asset')` in all your twig templates, besides all the other default functions provided by the original twig service provider

License
-------
The SmartRock FacebookServiceProvider is licensed under the MIT license.

[1]: http://silex.sensiolabs.org/
[2]: http://getcomposer.org/
[3]: http://getcomposer.org/composer.phar