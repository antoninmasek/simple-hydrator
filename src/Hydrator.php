<?php

namespace AntoninMasek\SimpleHydrator;

use AntoninMasek\SimpleHydrator\Attributes\Collection;
use AntoninMasek\SimpleHydrator\Casters\Caster;
use AntoninMasek\SimpleHydrator\Exceptions\CasterException;
use AntoninMasek\SimpleHydrator\Exceptions\UnknownCasterException;
use AntoninMasek\SimpleHydrator\Support\Arr;
use AntoninMasek\SimpleHydrator\Support\Str;

abstract class Hydrator
{
    /**
     * @throws CasterException
     * @throws Exceptions\InvalidCasterException
     */
    public static function hydrate(string $className, array $data = null): ?object
    {
        if (empty($data)) {
            return null;
        }

        $data = Arr::mapKeys($data, function ($key) {
            return Str::removeInvalidCharacters($key);
        });

        $reflectionClass = new \ReflectionObject($dto = new $className());
        $publicProperties = $reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($publicProperties as $property) {
            $value = array_key_exists($property->getName(), $data)
                ? $data[$property->getName()]
                : null;

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
    private static function hydrateProperty(\ReflectionProperty $property, mixed $value): mixed
    {
        if (($attributes = $property->getAttributes(Collection::class)) && is_array($value)) {
            $targetClassName = $attributes[0]->getArguments()[0];

            $value = array_map(function (mixed $item) use ($targetClassName) {
                if (Caster::existsFor($targetClassName) || ! is_array($item)) {
                    return self::cast($targetClassName, $item);
                }

                return self::hydrate($targetClassName, $item);
            }, $value);
        }

        if ($property->getType()->isBuiltin()) {
            return $value;
        }

        return self::cast($property->getType()->getName(), $value);
    }

    /**
     * @throws Exceptions\InvalidCasterException
     * @throws CasterException
     */
    private static function cast(string $className, mixed $value): mixed
    {
        try {
            return Caster::make($className)->cast($value);
        } catch (UnknownCasterException) {
            if (! is_null($value) && ! is_array($value)) {
                throw CasterException::invalidValue($className, $value);
            }

            return self::hydrate($className, $value);
        }
    }
}
