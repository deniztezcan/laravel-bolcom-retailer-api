<?php

namespace DenizTezcan\BolRetailerV3\Entities;

use DenizTezcan\BolRetailerV3\Models\Event;
use DenizTezcan\BolRetailerV3\Models\Order as OrderModel;
use DenizTezcan\BolRetailerV3\Models\Orders;
use DenizTezcan\BolRetailerV3\Support\Serialize;

class Order extends Entity
{
    public function getOrders(): Orders
    {
        $response = $this->client->authenticatedRequest('GET', 'orders');
        $deserialized = Serialize::deserialize((string) $response->getBody());

        return Orders::fromResponse($deserialized);
    }

    public function getOrder(string $orderId): OrderModel
    {
        $response = $this->client->authenticatedRequest('GET', 'orders/'.$orderId);
        $deserialized = Serialize::deserialize((string) $response->getBody());

        return OrderModel::fromResponse($deserialized);
    }

    public function cancelOrderItem(
        string $orderItemId,
        string $reasonCode
    ): Event {
        $response = $this->client->authenticatedRequest('PUT', 'orders/'.$orderItemId.'/cancellation', [
            'reasonCode' => $reasonCode,
        ]);

        $deserialized = Serialize::deserialize((string) $response->getBody());

        return Event::fromResponse($deserialized);
    }

    public function shipOrderItem(
        string $orderItemId,
        string $shipmentReference,
        string $transporterCode,
        string $trackAndTrace
    ): Event {
        $response = $this->client->authenticatedRequest('PUT', 'orders/'.$orderItemId.'/shipment', [
            'shipmentReference' => $shipmentReference,
            'transport'         => [
                'transporterCode'   => $transporterCode,
                'trackAndTrace'     => $trackAndTrace,
            ],
        ]);

        $deserialized = Serialize::deserialize((string) $response->getBody());

        return Event::fromResponse($deserialized);
    }
}
