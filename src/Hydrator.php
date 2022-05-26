<?php

namespace AntoninMasek\SimpleHydrator;

use ReflectionObject;
use ReflectionProperty;

abstract class Hydrator
{
    public static function hydrateRaw(string $className, array $data = null): ?object
    {
        if (empty($data)) {
            return null;
        }

        $reflectionClass   = new ReflectionObject($dto = new $className());
        $publicProperties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($publicProperties as $property) {
            $value = array_key_exists($property->getName(), $data)
                ? $data[$property->getName()]
                : null;

            if (! $property->getType()->isBuiltin()) {
                $value = self::hydrateRaw($property->getType()->getName(), $value);
            }

            $property->setValue(
                $dto,
                $value,
            );
        }

        return $dto;
    }

    public static function hydrate(array $data = null): ?static
    {
        return self::hydrateRaw(static::class, $data);
    }
}
