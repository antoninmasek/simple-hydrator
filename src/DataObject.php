<?php

namespace AntoninMasek\SimpleHydrator;

use AntoninMasek\SimpleHydrator\Exceptions\InvalidInputDataException;
use AntoninMasek\SimpleHydrator\Support\Arr;
use AntoninMasek\SimpleHydrator\Support\Str;

class DataObject
{
    public static function make(...$arguments): self
    {
        return new static(...$arguments);
    }

    /**
     * @throws InvalidInputDataException
     * @throws Exceptions\CasterException
     * @throws Exceptions\InvalidCasterException
     */
    public static function fromArray(array $data = null): ?static
    {
        if (Arr::isList($data)) {
            throw InvalidInputDataException::unexpectedArrayList();
        }

        return Hydrator::hydrate(static::class, $data);
    }

    /**
     * @return array<static>|null
     *
     * @throws InvalidInputDataException
     * @throws Exceptions\CasterException
     * @throws Exceptions\InvalidCasterException
     */
    public static function collectionFromArray(array $data = []): ?array
    {
        if (Arr::isAssoc($data)) {
            throw InvalidInputDataException::unexpectedAssociativeArray();
        }

        return array_map(function ($value) {
            return static::fromArray((array) $value);
        }, $data);
    }

    public function set(string $propertyName, mixed $value): static
    {
        $clone = clone $this;
        $reflectionClass = new \ReflectionObject($clone);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);

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
