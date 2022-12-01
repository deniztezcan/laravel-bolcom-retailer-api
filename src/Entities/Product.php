<?php

namespace DenizTezcan\BolRetailer\Entities;

use DenizTezcan\BolRetailer\Models\Event;
use DenizTezcan\BolRetailer\Support\Serialize;

class Product extends Entity
{

    public function createProduct(
        array $attributes,
        array $assets
    ): Event {
        $response = $this->client->authenticatedRequest('PUT', '/content/products', [
            'attributes' => $attributes,
            'assets' => $assets,
        ]);

        $deserialized = Serialize::deserialize((string) $response->getBody());

        return Event::fromResponse($deserialized);
    }

}