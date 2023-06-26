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

    public static function removeInvalidCharacters($value): ?string
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '', $value);
    }
}
