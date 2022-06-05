<?php

namespace AntoninMasek\SimpleHydrator\Casters;

use DateTime;

class DateTimeCaster extends Caster
{
    public function cast(mixed $value): ?DateTime
    {
        if (is_null($value)) {
            return null;
        }

        return new DateTime($value);
    }
}
