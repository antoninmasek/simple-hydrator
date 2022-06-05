<?php

namespace AntoninMasek\SimpleHydrator;

final class Helper
{
    public static function camel($value): ?string
    {
        $words = explode(' ', str_replace(['-', '_'], ' ', $value));

        $words = implode(array_map(fn ($word) => ucfirst($word), $words));

        return lcfirst($words);
    }
}
