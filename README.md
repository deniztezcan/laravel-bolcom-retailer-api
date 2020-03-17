# Laravel Bol.com Retailer v3 API
[![Latest Stable Version](https://poser.pugx.org/deniztezcan/laravel-bolcom-v3-api/v/stable)](https://packagist.org/packages/deniztezcan/laravel-bolcom-v3-api) 
[![Total Downloads](https://poser.pugx.org/deniztezcan/laravel-bolcom-v3-api/downloads)](https://packagist.org/packages/deniztezcan/laravel-bolcom-v3-api) 
[![Latest Unstable Version](https://poser.pugx.org/deniztezcan/laravel-bolcom-v3-api/v/unstable)](https://packagist.org/packages/deniztezcan/laravel-bolcom-v3-api) 
[![License](https://poser.pugx.org/deniztezcan/laravel-bolcom-v3-api/license)](https://packagist.org/packages/deniztezcan/laravel-bolcom-v3-api)

A Laravel package for the Bol.com v3 Retailer API. Losely based on the incomplete `jasperverbeet/bolcom-retailer-api-v3-php` package.

### Instalation
```
composer require deniztezcan/laravel-bolcom-v3-api
```

Add a ServiceProvider to your providers array in `config/app.php`:
```php
    'providers' => [
    	//other things here

    	DenizTezcan\\BolRetailerV3\\BolServiceProvider::class,
    ];
```

Add the facade to the facades array:
```php
    'aliases' => [
    	//other things here

    	'BolRetailerV3' => DenizTezcan\\BolRetailerV3\\Facades\\BolRetailerV3::class,
    ];
```

Finally, publish the configuration files:
```
php artisan vendor:publish --provider="DenizTezcan\\BolRetailerV3\\BolServiceProvider"
```

### Configuration
Please set your API: `key` and `secret` in the `config/bolcom-retailer-v3.php`