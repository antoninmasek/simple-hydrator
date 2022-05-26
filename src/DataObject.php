<?php

namespace AntoninMasek\SimpleHydrator;

abstract class DataObject
{
    public static function fromArray(array $data = null): ?static
    {
        return Hydrator::hydrate(static::class, $data);
    }
}
