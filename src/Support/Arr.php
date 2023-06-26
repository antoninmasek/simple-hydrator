<?php

namespace AntoninMasek\SimpleHydrator\Support;

use Closure;

/**
 * https://github.com/laravel/framework/blob/9.x/src/Illuminate/Collections/Arr.php.
 */
class Arr
{
    /**
     * Determines if an array is associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     */
    public static function isAssoc(array $array): bool
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    /**
     * Determines if an array is a list.
     *
     * An array is a "list" if all array keys are sequential integers starting from 0 with no gaps in between.
     */
    public static function isList(array $array): bool
    {
        return ! self::isAssoc($array);
    }

    /**
     * Maps the keys of the array
     */
    public static function mapKeys(array $array, Closure $callback): array
    {
        $newKeys = array_map(
            $callback,
            array_keys($array),
            $array,
        );

        return array_combine($newKeys, $array);
    }
}
