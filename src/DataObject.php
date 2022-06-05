<?php

namespace AntoninMasek\SimpleHydrator;

use AntoninMasek\SimpleHydrator\Support\Str;
use ReflectionObject;
use ReflectionProperty;

class DataObject
{
    public static function make(...$arguments): self
    {
        return new static(...$arguments);
    }

    public static function fromArray(array $data = null): ?static
    {
        return Hydrator::hydrate(static::class, $data);
    }

    public function __call($method, $arguments): self
    {
        $reflectionClass = new ReflectionObject($this);
        $properties      = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $name = $property->getName();

            if (Str::camel($name) === Str::camel($method)) {
                $property->setValue($this, ...$arguments);
            }
        }

        return $this;
    }
}
