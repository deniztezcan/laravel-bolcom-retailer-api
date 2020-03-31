# Laravel Bol.com Retailer v3 API
[![Latest Stable Version](https://poser.pugx.org/deniztezcan/laravel-bolcom-v3-api/v/stable)](https://packagist.org/packages/deniztezcan/laravel-bolcom-v3-api) 
[![Total Downloads](https://poser.pugx.org/deniztezcan/laravel-bolcom-v3-api/downloads)](https://packagist.org/packages/deniztezcan/laravel-bolcom-v3-api) 
[![Latest Unstable Version](https://poser.pugx.org/deniztezcan/laravel-bolcom-v3-api/v/unstable)](https://packagist.org/packages/deniztezcan/laravel-bolcom-v3-api) 
[![License](https://poser.pugx.org/deniztezcan/laravel-bolcom-v3-api/license)](https://packagist.org/packages/deniztezcan/laravel-bolcom-v3-api)

A Laravel package for the Bol.com v3 Retailer API. Losely based on the incomplete `jasperverbeet/bolcom-retailer-api-v3-php` package.

## Instalation
```
composer require deniztezcan/laravel-bolcom-v3-api
```

Add a ServiceProvider to your providers array in `config/app.php`:
```php
    'providers' => [
    	//other things here

    	DenizTezcan\BolRetailerV3\BolServiceProvider::class,
    ];
```

Add the facade to the facades array:
```php
    'aliases' => [
    	//other things here

    	'BolRetailerV3' => DenizTezcan\BolRetailerV3\Facades\BolRetailerV3::class,
    ];
```

Finally, publish the configuration files:
```
php artisan vendor:publish --provider="DenizTezcan\BolRetailerV3\BolServiceProvider"
```

### Configuration
Please set your API: `key` and `secret` in the `config/bolcom-retailer-v3.php`

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
$request = BolRetailerV3::commissions()->getCommissions([['ean' => '3615674428738'], ['ean' => '0958054542376'], ['ean' => '1863180850327']]);
$commissions = $request->commissions;
```
#### Single
To get commissions for the single EAN.
```php
$commission = BolRetailerV3::commissions()->getCommission('3615674428738');
```

### Offers
#### Create
It is possible to create an offer via the v3 api
```php
BolRetailerV3::offers()->createOffer(
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
$event = BolRetailerV3::offers()->requestDump();
sleep(120); //it takes some time for bol to generate the CSV a sleep is needed to make sure the CSV is ready
$csv = BolRetailerV3::offers()->handleDumpRequest((string) $event->entityId);
```

#### Get Offer by Offer ID
You can get a specific offers by it's offer id
```php
$offer = BolRetailerV3::offers()->getOffer($offerId);
```

#### Update fulfilment promise
You can update the fulfilment promise of an offer by it's offer id
```php
BolRetailerV3::offers()->updateOffer(
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
BolRetailerV3::offers()->updateOfferPrice(
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
BolRetailerV3::offers()->updateOfferStock(
	$offerId,
	$amount,
	$managedByRetailer
);
```

### Orders
####  Get open orders
```php
$orders = BolRetailerV3::orders()->getOrders();
```

####  Get order by order-id
```php
$order = BolRetailerV3::orders()->getOrder($orderId);
```

####  Cancel order by order-item-id
```php
BolRetailerV3::orders()->cancelOrderItem(
	$orderItemId,
	$reasonCode //"OUT_OF_STOCK" "REQUESTED_BY_CUSTOMER" "BAD_CONDITION" "HIGHER_SHIPCOST" "INCORRECT_PRICE" "NOT_AVAIL_IN_TIME" "NO_BOL_GUARANTEE" "ORDERED_TWICE" "RETAIN_ITEM" "TECH_ISSUE" "UNFINDABLE_ITEM" "OTHER"
);
```

####  Ship order by order-item-id
```php
BolRetailerV3::orders()->cancelOrderItem(
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
POST | /retailer/commission | v1.1.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/get-commissions)
GET | /retailer/commission/{ean} | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/get-commission)
POST | /retailer/offers | v1.1.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/post-offer)
POST | /retailer/offers/export | v1.3.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/post-offer-export)
GET | /retailer/offers/export/{offer-export-id} | v1.3.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/get-offer-export)
GET | /retailer/offers/{offer-id} | v1.1.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/get-offer)
PUT | /retailer/offers/{offer-id} | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/put-offer)
PUT | /retailer/offers/{offer-id}/price | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/update-offer-price)
PUT | /retailer/offers/{offer-id}/stock | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/update-offer-stock)
GET | /retailer/orders | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/get-orders)
GET | /retailer/orders/{orders-id} | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/get-order)
PUT | /retailer/orders/{order-item-id}/cancellation | v1.1.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/cancel-order)
PUT | /retailer/orders/{order-item-id}/shipment | v1.0.0 | [link](https://api.bol.com/retailer/public/redoc/v3#operation/ship-order-item)
