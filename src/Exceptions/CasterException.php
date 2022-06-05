<?php

namespace AntoninMasek\SimpleHydrator\Exceptions;

use Exception;

class CasterException extends Exception
{
    public static function invalidValue(string $className, mixed $value): static
    {
        return new static("Array expected. Got $value. Cannot tell how to build $className from $value. To solve this you can write your own caster. To find out how, take a look at 'Casters' section in the readme.");
    }
}
