<?php

namespace AntoninMasek\SimpleHydrator\Casters;

use AntoninMasek\SimpleHydrator\Exceptions\InvalidCasterException;
use AntoninMasek\SimpleHydrator\Exceptions\UnknownCasterException;
use ReflectionProperty;

abstract class Caster
{
    private const CASTERS_NAMESPACE = 'AntoninMasek\SimpleHydrator\Casters';
    private const CASTERS_SUFFIX    = 'Caster';

    private static array $casters = [];

    public function __construct(protected ReflectionProperty $property)
    {
    }

    public static function setCasters(array $map): array
    {
        return self::$casters = $map;
    }

    public static function registerCaster(string $className, string $casterClassName): array
    {
        return self::setCasters(array_merge(self::$casters, [
            $className => $casterClassName,
        ]));
    }

    /**
     * @throws InvalidCasterException
     * @throws UnknownCasterException
     */
    public static function make(ReflectionProperty $property): Caster
    {
        $propertyClassName = $property->getType()->getName();

        $casterClassName   = ! array_key_exists($propertyClassName, self::$casters)
            ? self::CASTERS_NAMESPACE . "\\$propertyClassName" . self::CASTERS_SUFFIX
            : self::$casters[$propertyClassName];

        if (! class_exists($casterClassName)) {
            throw new UnknownCasterException($casterClassName);
        }

        $caster = new $casterClassName($property);

        if (! ($caster instanceof Caster)) {
            throw new InvalidCasterException();
        }

        return $caster;
    }

    abstract public function cast(mixed $value): mixed;
}
