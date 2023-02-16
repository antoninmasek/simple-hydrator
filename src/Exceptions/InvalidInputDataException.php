<?php

namespace AntoninMasek\SimpleHydrator\Exceptions;

class InvalidInputDataException extends \Exception
{
    public static function unexpectedArrayList(): static
    {
        return new static('Associative array expected to cast data to an object. If your input data is an array of objects, please consider using `collectionFromArray` method instead');
    }

    public static function unexpectedAssociativeArray(): static
    {
        return new static('List array expected to cast array to an array of object. If your input data is an associative array representing an object, please consider using `fromArray` method instead');
    }
}
