<?php

namespace AntoninMasek\SimpleHydrator\Casters;

use AntoninMasek\SimpleHydrator\Exceptions\InvalidCasterException;
use AntoninMasek\SimpleHydrator\Exceptions\UnknownCasterException;

abstract class Caster
{
    private const CASTERS_NAMESPACE = 'AntoninMasek\SimpleHydrator\Casters';

    private const CASTERS_SUFFIX = 'Caster';

    private static array $casters = [];

    public function __construct(protected string $class_name, protected bool $allows_null = false)
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
        return self::setCasters(
            array_merge(self::$casters, [
                $className => $caster,
            ])
        );
    }

    public static function existsFor(string $className): bool
    {
        return array_key_exists($className, self::$casters);
    }

    /**
     * @throws InvalidCasterException
     * @throws UnknownCasterException
     */
    public static function make(string $className, bool $allowsNull): Caster
    {
        $casterClassNameOrCallable = ! array_key_exists($className, self::$casters)
            ? self::CASTERS_NAMESPACE."\\$className".self::CASTERS_SUFFIX
            : self::$casters[$className];

        if (is_callable($casterClassNameOrCallable)) {
            return self::handleCallableCaster($casterClassNameOrCallable);
        }

        if (! class_exists($casterClassNameOrCallable) && enum_exists($className)) {
            return new EnumCaster($className, $allowsNull);
        }

        if (! class_exists($casterClassNameOrCallable)) {
            throw new UnknownCasterException($casterClassNameOrCallable);
        }

        $caster = new $casterClassNameOrCallable($className, $allowsNull);

        if (! ($caster instanceof Caster)) {
            throw new InvalidCasterException();
        }

        return $caster;
    }

    private static function handleCallableCaster($callable): Caster
    {
        return new class($callable) extends Caster
        {
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
