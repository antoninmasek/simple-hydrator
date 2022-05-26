<?php

namespace AntoninMasek\SimpleHydrator;

use DateTime;
use ReflectionObject;
use ReflectionProperty;

abstract class Hydrator
{
    public static function hydrate(string $className, array $data = null): ?object
    {
        if (empty($data)) {
            return null;
        }

        $reflectionClass = new ReflectionObject($dto = new $className());
        $publicProperties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($publicProperties as $property) {
            $value = array_key_exists($property->getName(), $data)
                ? $data[$property->getName()]
                : null;

            if (! $property->getType()->isBuiltin()) {
                $value = match ($property->getType()->getName()) {
                    DateTime::class => new DateTime($value),
                    default => self::hydrate($property->getType()->getName(), $value),
                };
            }

            $property->setValue(
                $dto,
                $value,
            );
        }

        return $dto;
    }
}
