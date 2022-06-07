<?php

namespace AntoninMasek\SimpleHydrator;

use AntoninMasek\SimpleHydrator\Support\Arr;
use AntoninMasek\SimpleHydrator\Support\Str;
use ReflectionObject;
use ReflectionProperty;

class DataObject
{
    public static function make(...$arguments): self
    {
        return new static(...$arguments);
    }

    public static function fromArray(array $data = null): array|null|static
    {
        if (! Arr::isList($data)) {
            return Hydrator::hydrate(static::class, $data);
        }

        return Arr::map($data, function ($value) {
            return static::fromArray((array) $value);
        });
    }

    public function set(string $propertyName, mixed $value): static
    {
        $clone           = clone $this;
        $reflectionClass = new ReflectionObject($clone);
        $properties      = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $name = $property->getName();

            if (Str::camel($name) === Str::camel($propertyName)) {
                $property->setValue($clone, $value);
            }
        }

        return $clone;
    }

    public function __call($method, $arguments): static
    {
        return $this->set($method, ...$arguments);
    }
}
