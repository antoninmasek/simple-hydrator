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

    public static function clearCasters(): array
    {
        return self::$casters = [];
    }

    public static function registerCaster(string $className, string|callable $caster): array
    {
        return self::setCasters(array_merge(self::$casters, [
            $className => $caster,
        ]));
    }

    /**
     * @throws InvalidCasterException
     * @throws UnknownCasterException
     */
    public static function make(ReflectionProperty $property): Caster
    {
        $propertyClassName = $property->getType()->getName();

        $casterClassNameOrCallable   = ! array_key_exists($propertyClassName, self::$casters)
            ? self::CASTERS_NAMESPACE . "\\$propertyClassName" . self::CASTERS_SUFFIX
            : self::$casters[$propertyClassName];

        if (is_callable($casterClassNameOrCallable)) {
            return self::handleCallableCaster($casterClassNameOrCallable);
        }

        if (! class_exists($casterClassNameOrCallable)) {
            throw new UnknownCasterException($casterClassNameOrCallable);
        }

        $caster = new $casterClassNameOrCallable($property);

        if (! ($caster instanceof Caster)) {
            throw new InvalidCasterException();
        }

        return $caster;
    }

    private static function handleCallableCaster($callable): Caster
    {
        return new class($callable) extends Caster {
            public function __construct(private mixed $callable)
            {
            }

            public function cast(mixed $value): mixed
            {
                return ($this->callable)($value);
            }
        };
    }

    abstract public function cast(mixed $value): mixed;
}
