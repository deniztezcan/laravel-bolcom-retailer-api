<?php

namespace DenizTezcan\BolRetailer\Entities;

class Entity
{
    protected $client = null;

    public function __construct($client)
    {
        $this->client = $client;
    }
}
