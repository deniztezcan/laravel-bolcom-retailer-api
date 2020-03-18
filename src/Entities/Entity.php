<?php

namespace DenizTezcan\BolRetailerV3\Entities;

use Exception;
use ReflectionClass;

class Entity
{
	protected $client = null;

    public function __construct($client)
    {
    	$this->client = $client;
    }
}