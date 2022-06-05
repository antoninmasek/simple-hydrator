<?php

namespace AntoninMasek\SimpleHydrator\Exceptions;

use Exception;

class CasterException extends Exception
{
    public static function unknownCaster(string $classname): static
    {
        return new static("Unknown caster $classname");
    }
}
