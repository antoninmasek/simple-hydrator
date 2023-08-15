<?php

namespace AntoninMasek\SimpleHydrator\Casters;

class EnumCaster extends Caster
{
    public function cast(mixed $value): mixed
    {
        if (! enum_exists($this->class_name)) {
            return $value;
        }

        if (is_null($value) && $this->allows_null) {
            return null;
        }

        return $this->class_name::from($value);
    }
}
