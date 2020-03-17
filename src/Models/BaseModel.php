<?php

namespace DenizTezcan\BolRetailerV3\Models;

use Exception;
use ReflectionClass;

abstract class BaseModel
{
	public function assertType($var, string $type)
    {
        if (gettype($var) != $type)
        {
            throw new Exception;
        }
    }

    static private function createObject($deserialized) : object
    {
        $ref = new ReflectionClass(static::class);
        $instance = $ref->newInstanceWithoutConstructor();

        foreach ($deserialized as $propertyName => $propertyValue) {
            $propRef = $ref->getProperty($propertyName);
            $propRef->setAccessible(true);
            $propRef->setValue($instance, $propertyValue);
        }

        return $instance;
    }

    static function fromResponse(object $deserialized): object
    {
        $instance = self::createObject($deserialized);
        $instance->validate();
        return $instance;
    }

    static function manyFromResponse(array $deserialized): array
    {
        $itemList = array();

        foreach($deserialized as $item)
        {
            $instance = self::createObject($item);
            $instance->validate();
            array_push($itemList, $instance);
        }
        return $itemList;
    }

    abstract function validate(): void;
}