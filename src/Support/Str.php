<?php

namespace AntoninMasek\SimpleHydrator\Support;

final class Str
{
    public static function camel($value): ?string
    {
        $words = explode(' ', str_replace(['-', '_'], ' ', $value));

        $words = implode(array_map(fn ($word) => ucfirst($word), $words));

        return lcfirst($words);
    }
}
