<?php

namespace DenizTezcan\BolRetailerV3\Entities;

use DenizTezcan\BolRetailerV3\Models\Event;
use DenizTezcan\BolRetailerV3\Models\Offer as OfferModel;
use DenizTezcan\BolRetailerV3\Support\Serialize;

class Offer extends Entity
{
    public function createOffer(
        string $ean,
        string $conditionName,
        string $conditionCategory,
        string $referenceCode,
        bool $onHoldByRetailer,
        string $unknownProductTitle,
        float $price,
        int $stockAmount,
        bool $stockManagedByRetailer,
        string $fulfilmentType,
        string $fulfilmentDeliveryCode
    ): Event {
        $response = $this->client->authenticatedRequest('POST', 'offers', [
            'ean'                   => $ean,
            'condition'             => [
                'name'                  => $conditionName,
                'category'              => $conditionCategory,
            ],
            'referenceCode'         => $referenceCode,
            'onHoldByRetailer'      => $onHoldByRetailer,
            'unknownProductTitle'   => $unknownProductTitle,
            'pricing'               => [
                'bundlePrices'          => [
                    [
                        'quantity'          => 1,
                        'price'             => $price,
                    ],
                ],
            ],
            'stock'                 => [
                'amount'                => $stockAmount,
                'managedByRetailer'     => $stockManagedByRetailer,
            ],
            'fulfilment'            => [
                'type'                  => $fulfilmentType,
                'deliveryCode'          => $fulfilmentDeliveryCode,
            ],
        ]);

        $deserialized = Serialize::deserialize((string) $response->getBody());

        return Event::fromResponse($deserialized);
    }

    public function requestDump(): Event
    {
        $response = $this->client->authenticatedRequest('POST', 'offers/export', [
            'format' => 'CSV',
        ]);
        $deserialized = Serialize::deserialize((string) $response->getBody());

        return Event::fromResponse($deserialized);
    }

    public function handleDumpRequest(string $entityId): string
    {
        $response = $this->client->authenticatedRequest('GET', 'process-status/'.$entityId, [
            'entity-id'     => $entityId,
            'event-type'    => 'CREATE_OFFER_EXPORT',
        ]);
        $deserialized = Serialize::deserialize((string) $response->getBody());
        $event = Event::fromResponse($deserialized);

        if ($event->status == 'SUCCESS') {
            $response = $this->client->authenticatedRequest('GET', 'offers/export/'.$event->entityId, [], ['Accept' => 'application/vnd.retailer.v3+csv']);

            return (string) $response->getBody();
        } else {
            sleep(120);

            return $this->handleDumpRequest($entityId);
        }
    }

    public function getOffer(string $offerId): OfferModel
    {
        $response = $this->client->authenticatedRequest('GET', 'offers/'.$offerId);
        $deserialized = Serialize::deserialize((string) $response->getBody());

        return OfferModel::fromResponse($deserialized);
    }

    public function updateOffer(
        string $offerId,
        string $referenceCode,
        bool $onHoldByRetailer,
        string $unknownProductTitle,
        string $fulfilmentType,
        string $fulfilmentDeliveryCode
    ): Event {
        $response = $this->client->authenticatedRequest('PUT', 'offers/'.$offerId, [
            'referenceCode'         => $referenceCode,
            'onHoldByRetailer'      => $onHoldByRetailer,
            'unknownProductTitle'   => $unknownProductTitle,
            'fulfilment'            => [
                'type'                  => $fulfilmentType,
                'deliveryCode'          => $fulfilmentDeliveryCode,
            ],
        ]);

        $deserialized = Serialize::deserialize((string) $response->getBody());

        return Event::fromResponse($deserialized);
    }

    public function updateOfferPrice(
        string $offerId,
        array $bundlePrices
    ): Event {
        $response = $this->client->authenticatedRequest('PUT', 'offers/'.$offerId.'/price', [
            'pricing' => [
                'bundlePrices' => $bundlePrices,
            ],
        ]);

        $deserialized = Serialize::deserialize((string) $response->getBody());

        return Event::fromResponse($deserialized);
    }

    public function updateOfferStock(
        string $offerId,
        int $amount,
        bool $managedByRetailer
    ): Event {
        $response = $this->client->authenticatedRequest('PUT', 'offers/'.$offerId.'/stock', [
            'amount'            => $amount,
            'managedByRetailer' => $managedByRetailer,
        ]);

        $deserialized = Serialize::deserialize((string) $response->getBody());

        return Event::fromResponse($deserialized);
    }
}
