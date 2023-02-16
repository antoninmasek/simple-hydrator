<?php

namespace AntoninMasek\SimpleHydrator\Exceptions;

use AntoninMasek\SimpleHydrator\Casters\Caster;

class InvalidCasterException extends \Exception
{
    public function __construct()
    {
        parent::__construct('All casters have to extend '.Caster::class);
    }
}
