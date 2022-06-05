<?php

namespace AntoninMasek\SimpleHydrator\Exceptions;

use Exception;

class UnknownCasterException extends Exception
{
    public function __construct(string $className)
    {
        parent::__construct("Unknown caster $className");
    }
}
