<?php

namespace AntoninMasek\SimpleHydrator;

use AntoninMasek\SimpleHydrator\Attributes\Collection;
use AntoninMasek\SimpleHydrator\Casters\Caster;
use AntoninMasek\SimpleHydrator\Exceptions\CasterException;
use AntoninMasek\SimpleHydrator\Exceptions\UnknownCasterException;
use ReflectionObject;
use ReflectionProperty;

abstract class Hydrator
{
    public static function hydrate(string $className, array $data = null): ?object
    {
        if (empty($data)) {
            return null;
        }

        $reflectionClass  = new ReflectionObject($dto = new $className());
        $publicProperties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($publicProperties as $property) {
            $value = array_key_exists($property->getName(), $data)
                ? $data[$property->getName()]
                : null;

            if (($attributes = $property->getAttributes(Collection::class)) && is_array($value)) {
                $value = array_map(function (mixed $item) use ($attributes) {
                    return self::hydrate($attributes[0]->getArguments()[0], $item);
                }, $value);
            }

            if ($property->getType()->isBuiltin()) {
                $property->setValue($dto, $value);
                continue;
            }

            try {
                $value = Caster::make($property)->cast($value);
            } catch (UnknownCasterException) {
                if (! is_null($value) && ! is_array($value)) {
                    throw CasterException::invalidValue($property->getType()->getName(), $value);
                }

                $value = self::hydrate($property->getType()->getName(), $value);
            }

            $property->setValue($dto, $value);
        }

        return $dto;
    }
}
