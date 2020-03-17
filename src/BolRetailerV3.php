<?php

namespace DenizTezcan\BolRetailerV3;

use DenizTezcan\BolRetailerV3\Http\Client;
use DenizTezcan\BolRetailerV3\Support\Serialize;
use DenizTezcan\BolRetailerV3\Models\Event;
use DenizTezcan\BolRetailerV3\Models\Orders;
use DenizTezcan\BolRetailerV3\Models\Order;
use Exception;

class BolRetailerV3
{
	private $client = null;

	public function __construct()
    {
    	if ($this->client === null) {
            $this->client = new Client(config('bolcom-retailer-v3.api.client_id'), config('bolcom-retailer-v3.api.client_secret'));
        }
        $this->client->authenticate();
    }

    public function setDemoMode(): void
    {
    	$this->client->setDemoMode(true);
    }

    public function updateOffer(
    	string $offerId,
    	string $referenceCode,
    	bool $onHoldByRetailer,
    	string $unknownProductTitle,
    	string $fulfilmentType,
    	string $fulfilmentDeliveryCode
    ): Event
    {
    	$response = $this->client->authenticatedRequest("PUT", "offers/".$offerId, [
    		'referenceCode' 		=> $referenceCode,
    		'onHoldByRetailer' 		=> $onHoldByRetailer,
    		'unknownProductTitle' 	=> $unknownProductTitle,
	        "fulfilment" 			=> [
	            "type" 					=> $fulfilmentType,
	            "deliveryCode" 			=> $fulfilmentDeliveryCode
	        ]
        ]);

        $deserialized = Serializer::deserialize((string)$response->getBody());
        return Event::fromResponse($deserialized);
    }

    public function updateOfferPrice(
    	string $offerId,
    	array $bundlePrices
    ): Event
    {
    	$response = $this->client->authenticatedRequest("PUT", "offers/".$offerId."/price", [
    		"pricing" => [
    			"bundlePrices" => $bundlePrices
	        ]
        ]);

        $deserialized = Serializer::deserialize((string)$response->getBody());
        return Event::fromResponse($deserialized);
    }

    public function updateOfferStock(
    	string $offerId,
    	int $amount,
    	bool $managedByRetailer
    ): Event
    {
    	$response = $this->client->authenticatedRequest("PUT", "offers/".$offerId."/stock", [
    		"amount" => $amount,
    		"managedByRetailer" => $managedByRetailer
        ]);

        $deserialized = Serializer::deserialize((string)$response->getBody());
        return Event::fromResponse($deserialized);
    }

    public function getOrders(): Orders
    {
    	$response = $this->client->authenticatedRequest("GET", "orders");
    	$deserialized = Serializer::deserialize((string)$response->getBody());
        return Orders::manyFromResponse($deserialized);
    }

     public function getOrder(string $orderId): Order
    {
    	$response = $this->client->authenticatedRequest("GET", "orders/".$orderId);
    	$deserialized = Serializer::deserialize((string)$response->getBody());
        return Order::fromResponse($deserialized);
    }

    public function addOrderShipment
    (
    	string $orderId,
    	string $shipmentReference,
    	string $transporterCode,
    	string $trackAndTrace
    ): Event
    {
    	$response = $this->client->authenticatedRequest("PUT", "orders/".$orderId."/shipment", [
    		'shipmentReference' => $shipmentReference,
	        "transport" 		=> [
	            "transporterCode" 	=> $transporterCode,
	            "trackAndTrace" 	=> $trackAndTrace
	        ]
        ]);

        $deserialized = Serializer::deserialize((string)$response->getBody());
        return Event::fromResponse($deserialized);
    }
}