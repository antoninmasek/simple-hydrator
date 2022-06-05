<?php

namespace AntoninMasek\SimpleHydrator\Casters;

use AntoninMasek\SimpleHydrator\Exceptions\CasterException;
use ReflectionProperty;

abstract class AbstractCaster
{
    private const CASTERS_NAMESPACE = 'AntoninMasek\SimpleHydrator\Casters';
    private const CASTERS_SUFFIX    = 'Caster';

    public function __construct(protected ReflectionProperty $property)
    {
    }

    /**
     * @throws CasterException
     */
    public static function make(ReflectionProperty $property): AbstractCaster
    {
        $propertyBaseClassName = $property->getType()->getName();

        $casterClassName = self::CASTERS_NAMESPACE . "\\$propertyBaseClassName" . self::CASTERS_SUFFIX;

        if (! class_exists($casterClassName)) {
            throw CasterException::unknownCaster($casterClassName);
        }

        return new $casterClassName($property);
    }

    abstract public function cast(mixed $value): mixed;
}
