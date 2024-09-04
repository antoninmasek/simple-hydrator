<?php

namespace AntoninMasek\SimpleHydrator;

use AntoninMasek\SimpleHydrator\Attributes\Collection;
use AntoninMasek\SimpleHydrator\Attributes\Key;
use AntoninMasek\SimpleHydrator\Casters\Caster;
use AntoninMasek\SimpleHydrator\Exceptions\CasterException;
use AntoninMasek\SimpleHydrator\Exceptions\UnknownCasterException;
use ReflectionProperty;

abstract class Hydrator
{
    /**
     * @throws CasterException
     * @throws Exceptions\InvalidCasterException
     */
    public static function hydrate(string $className, ?array $data = null): ?object
    {
        if (empty($data)) {
            return null;
        }

        $reflectionClass = new \ReflectionObject($dto = new $className);
        $publicProperties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($publicProperties as $property) {
            $attributes = $property->getAttributes(Key::class);

            $key = ! empty($attributes)
                ? $attributes[0]->getArguments()[0]
                : $property->getName();

            $value = ! array_key_exists($key, $data)
                ? $property->getDefaultValue()
                : $data[$key];

            $property->setValue(
                $dto,
                self::hydrateProperty($property, $value)
            );
        }

        return $dto;
    }

    /**
     * @throws CasterException
     * @throws Exceptions\InvalidCasterException
     */
    private static function hydrateProperty(ReflectionProperty $property, mixed $value): mixed
    {
        $allowsNull = $property->getType()->allowsNull();

        if (($attributes = $property->getAttributes(Collection::class)) && is_array($value)) {
            $targetClassName = $attributes[0]->getArguments()[0];

            $value = array_map(function (mixed $item) use ($targetClassName, $allowsNull) {
                if (Caster::existsFor($targetClassName) || ! is_array($item)) {
                    return self::cast($targetClassName, $item, $allowsNull);
                }

                return self::hydrate($targetClassName, $item);
            }, $value);
        }

        if ($property->getType()->isBuiltin()) {
            return match ($property->getType()->getName()) {
                'bool' => filter_var($value, FILTER_VALIDATE_BOOL),
                default => $value,
            };
        }

        return self::cast($property->getType()->getName(), $value, $allowsNull);
    }

    /**
     * @throws Exceptions\InvalidCasterException
     * @throws CasterException
     */
    private static function cast(string $className, mixed $value, bool $allowsNull): mixed
    {
        try {
            return Caster::make($className, $allowsNull)->cast($value);
        } catch (UnknownCasterException) {
            if (! is_null($value) && ! is_array($value)) {
                throw CasterException::invalidValue($className, $value);
            }

            return self::hydrate($className, $value);
        }
    }
}
