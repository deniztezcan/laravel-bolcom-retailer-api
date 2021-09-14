# Laravel Bol.com Retailer v5 API
[![Latest Stable Version](https://poser.pugx.org/deniztezcan/laravel-bolcom-v3-api/v/stable)](https://packagist.org/packages/deniztezcan/laravel-bolcom-v3-api) 
[![Total Downloads](https://poser.pugx.org/deniztezcan/laravel-bolcom-v3-api/downloads)](https://packagist.org/packages/deniztezcan/laravel-bolcom-v3-api) 
[![Latest Unstable Version](https://poser.pugx.org/deniztezcan/laravel-bolcom-v3-api/v/unstable)](https://packagist.org/packages/deniztezcan/laravel-bolcom-v3-api) 
[![License](https://poser.pugx.org/deniztezcan/laravel-bolcom-v3-api/license)](https://packagist.org/packages/deniztezcan/laravel-bolcom-v3-api)
[![Maintainability](https://api.codeclimate.com/v1/badges/9057b79855fcc029f989/maintainability)](https://codeclimate.com/github/deniztezcan/laravel-bolcom-v3-api/maintainability)

A Laravel package for the Bol.com v5 Retailer API. Losely based on the incomplete `jasperverbeet/bolcom-retailer-api-v3-php` package.

## Instalation
```
composer require deniztezcan/laravel-bolcom-retailer-api
```

Add a ServiceProvider to your providers array in `config/app.php`:
```php
    'providers' => [
    	//other things here

    	DenizTezcan\BolRetailer\BolServiceProvider::class,
    ];
```

Add the facade to the facades array:
```php
    'aliases' => [
    	//other things here

    	'BolRetailerAPI' => DenizTezcan\BolRetailer\Facades\BolRetailerAPI::class,
    ];
```

Finally, publish the configuration files:
```
php artisan vendor:publish --provider="DenizTezcan\BolRetailer\BolServiceProvider"
```

### Configuration
Please set your API: `key` and `secret` in the `config/bolcom-retailer.php`

## How to use
* [Commission](#commission)
	* [Bulk](#bulk)
	* [Single](#single)
* [Offers](#offers)
	* [Create](#create)
	* [CSV dump](#csv-dump)
	* [Get Offer by Offer ID](#get-offer-by-offer-ID)
	* [Update offer price](#Update-offer-price)
	* [Update fulfilment promise](#Update-fulfilment-promise)
	* [Update offer stock](#Update-offer-stock)
* [Orders](#orders)
	* [Get open orders](#Get-open-orders)
	* [Get order by order-id](#Get-order-by-order-id)
	* [Cancel order by order-item-id](#Cancel-order-by-order-item-id)
	* [Ship order by order-item-id](#Ship-order-by-order-item-id)
* [All features](#features)

### Commission
#### Bulk
To get commissions in bulk, we need to send EANs in bulk.
```php
$request = BolRetailerAPI::commissions()->getCommissions([['ean' => '3615674428738'], ['ean' => '0958054542376'], ['ean' => '1863180850327']]);
$commissions = $request->commissions;
```
#### Single
To get commissions for the single EAN.
```php
$commission = BolRetailerAPI::commissions()->getCommission('3615674428738');
```

### Offers
#### Create
It is possible to create an offer via the v3 api
```php
BolRetailerAPI::offers()->createOffer(
	$ean,
	$conditionName, //  "NEW" "AS_NEW" "GOOD" "REASONABLE" "MODERATE",
	$conditionCategory, // "NEW" "SECONDHAND"
	$referenceCode, // Your internal SKU or other ID
	$onHoldByRetailer,
	$unknownProductTitle, 
	$price,
	$stockAmount,
	$stockManagedByRetailer, //False incase you want Bol to remove the stock automatically from their system based on orders
	$fulfilmentType, //"FBB" "FBR" (FBB - Fulfilment By Bol) (FBR - Fulfilment by Retailer)
	$fulfilmentDeliveryCode //"24uurs-23" "24uurs-22" "24uurs-21" "24uurs-20" "24uurs-19" "24uurs-18" "24uurs-17" "24uurs-16" "24uurs-15" "24uurs-14" "24uurs-13" "24uurs-12" "1-2d" "2-3d" "3-5d" "4-8d" "1-8d" "MijnLeverbelofte" 
);
```

#### CSV dump
To get a list of all offers you have in CSV
```php
$event = BolRetailerAPI::offers()->requestDump();
sleep(120); //it takes some time for bol to generate the CSV a sleep is needed to make sure the CSV is ready
$csv = BolRetailerAPI::offers()->handleDumpRequest((string) $event->entityId);
```

#### Get Offer by Offer ID
You can get a specific offers by it's offer id
```php
$offer = BolRetailerAPI::offers()->getOffer($offerId);
```

#### Update fulfilment promise
You can update the fulfilment promise of an offer by it's offer id
```php
BolRetailerAPI::offers()->updateOffer(
	$offerId,
	$referenceCode,
	$onHoldByRetailer,
	$unknownProductTitle,
	$fulfilmentType, //"FBB" "FBR" (FBB - Fulfilment By Bol) (FBR - Fulfilment by Retailer)
	$fulfilmentDeliveryCode //"24uurs-23" "24uurs-22" "24uurs-21" "24uurs-20" "24uurs-19" "24uurs-18" "24uurs-17" "24uurs-16" "24uurs-15" "24uurs-14" "24uurs-13" "24uurs-12" "1-2d" "2-3d" "3-5d" "4-8d" "1-8d" "MijnLeverbelofte" 
);
```

#### Update offer price
You can update the price of an offer by it's offer id
```php
BolRetailerAPI::offers()->updateOfferPrice(
	$offerId,
	[
		[
			'quantity' => 1,
			'price' => 1.00
		]
	]
);
```

#### Update offer stock
You can update the stock of an offer by it's offer id
```php
BolRetailerAPI::offers()->updateOfferStock(
	$offerId,
	$amount,
	$managedByRetailer
);
```

### Orders
####  Get open orders
```php
$orders = BolRetailerAPI::orders()->getOrders();
```

####  Get order by order-id
```php
$order = BolRetailerAPI::orders()->getOrder($orderId);
```

####  Cancel order by order-item-id
```php
BolRetailerAPI::orders()->cancelOrderItem(
	$orderItemId,
	$reasonCode //"OUT_OF_STOCK" "REQUESTED_BY_CUSTOMER" "BAD_CONDITION" "HIGHER_SHIPCOST" "INCORRECT_PRICE" "NOT_AVAIL_IN_TIME" "NO_BOL_GUARANTEE" "ORDERED_TWICE" "RETAIN_ITEM" "TECH_ISSUE" "UNFINDABLE_ITEM" "OTHER"
);
```

####  Ship order by order-item-id
```php
BolRetailerAPI::orders()->cancelOrderItem(
	$shipOrderItem,
	$shipmentReference, //optional only for internal purposes
	$transporterCode, // TNT for PostNL
	$trackAndTrace, // Track and Trace number
);
```

## Features
The following features are available (an - means the feature is planned, but not yet included):


Method | URI | From Version | Link to Bol documentation
--- | --- | --- | ---
POST | /retailer/commission | v1.1.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/get-commissions)
GET | /retailer/commission/{ean} | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/get-commission)
POST | /retailer/offers | v1.1.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/post-offer)
POST | /retailer/offers/export | v1.3.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/post-offer-export)
GET | /retailer/offers/export/{offer-export-id} | v1.3.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/get-offer-export)
GET | /retailer/offers/{offer-id} | v1.1.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/get-offer)
PUT | /retailer/offers/{offer-id} | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/put-offer)
PUT | /retailer/offers/{offer-id}/price | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/update-offer-price)
PUT | /retailer/offers/{offer-id}/stock | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/update-offer-stock)
GET | /retailer/orders | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/get-orders)
GET | /retailer/orders/{orders-id} | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/get-order)
PUT | /retailer/orders/{order-item-id}/cancellation | v1.1.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/cancel-order)
PUT | /retailer/orders/{order-item-id}/shipment | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v5#operation/ship-order-item)
