<?php

namespace DenizTezcan\BolRetailer;

use DenizTezcan\BolRetailer\Entities\Commission;
use DenizTezcan\BolRetailer\Entities\Offer;
use DenizTezcan\BolRetailer\Entities\Order;
use DenizTezcan\BolRetailer\Entities\Product;
use DenizTezcan\BolRetailer\Http\Client;

class BolRetailerAPI
{
    protected $client = null;

    public function __construct()
    {
        if ($this->client === null) {
            $this->client = new Client(config('bolcom-retailer.api.client_id'), config('bolcom-retailer.api.client_secret'));
        }
        $this->client->authenticate();
    }

    public function setClientIdSecret(
        string $clientId,
        string $clientSecret
    ): self {
        $this->client = new Client($clientId, $clientSecret);

        return $this;
    }

    public function setDemoMode(): void
    {
        $this->client->setDemoMode(true);
    }

    public function commisions(): Commission
    {
        return new Commission($this->client);
    }

    public function offers(): Offer
    {
        return new Offer($this->client);
    }

    public function orders(): Order
    {
        return new Order($this->client);
    }

    public function products(): Product
    {
        return new Product($this->client);
    }
}
