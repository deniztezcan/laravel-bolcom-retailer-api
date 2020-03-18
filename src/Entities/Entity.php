<?php

namespace DenizTezcan\BolRetailerV3\Entities;

class Entity
{
    protected $client = null;

    public function __construct($client)
    {
        $this->client = $client;
    }
}
