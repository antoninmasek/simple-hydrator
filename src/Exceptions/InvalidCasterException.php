<?php

namespace AntoninMasek\SimpleHydrator\Exceptions;

use AntoninMasek\SimpleHydrator\Casters\Caster;
use Exception;

class InvalidCasterException extends Exception
{
    public function __construct()
    {
        parent::__construct('All casters have to extend ' . Caster::class);
    }
}
