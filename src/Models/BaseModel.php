<?php

namespace DenizTezcan\BolRetailer\Models;

use Exception;
use ReflectionClass;

abstract class BaseModel
{
    public function assertType($var, string $type)
    {
        if (gettype($var) === null) {
            info('unexpected expected null error');
        } else {
            if (gettype($var) != $type) {
                throw new Exception('Variable is not of the type: '.$type.' but is of the type: '.gettype($var));
            }
        }
    }

    private static function createObject($deserialized): object
    {
        $ref = new ReflectionClass(static::class);
        $instance = $ref->newInstanceWithoutConstructor();

        foreach ($deserialized as $propertyName => $propertyValue) {
            if ($propertyName != 'errorMessage') {
                $propRef = $ref->getProperty($propertyName);
                $propRef->setAccessible(true);
                $propRef->setValue($instance, $propertyValue);
            }
        }

        return $instance;
    }

    public static function fromResponse(object $deserialized): object
    {
        $instance = self::createObject($deserialized);
        $instance->validate();

        return $instance;
    }

    public static function manyFromResponse(array $deserialized): array
    {
        $itemList = [];

        foreach ($deserialized as $item) {
            $instance = self::createObject($item);
            $instance->validate();
            array_push($itemList, $instance);
        }

        return $itemList;
    }

    abstract public function validate(): void;
}
