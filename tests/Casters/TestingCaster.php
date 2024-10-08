<?php

namespace AntoninMasek\SimpleHydrator\Tests\Casters;

use AntoninMasek\SimpleHydrator\Casters\Caster;
use AntoninMasek\SimpleHydrator\Tests\Models\ClassThatNeedsCustomCaster;

class TestingCaster extends Caster
{
    public function cast(mixed $value): ClassThatNeedsCustomCaster
    {
        $class = new ClassThatNeedsCustomCaster;

        $class->value = floatval((new \DateTime)->format('n')) + $value;

        return $class;
    }
}
