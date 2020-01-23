<?php

namespace onlinetests\Utils;

/**
 * Arrays static methods
 */
class Arrays
{
    public static function toString(array $array, string $separator = ', ', string $type = 'value', string $prefix = '', string $postfix = ''): string
    {
        $string = '';
        foreach ($array as $key => $value) {
            $string .= (($string != '' ? $separator : '') . $prefix . ($type == 'value' ? $value : $key) . $postfix);
        }
        return $string;
    }

    /**
     * Add Prefix and Postfix to keys andvalues
     * @param array $array
     * @param string $prefixKey
     * @param string $prefixValue
     * @param string $postfixKey
     * @param string $postfixValue
     * @return array
     */
    public static function addPrefixPostfix(array $array, string $prefixKey = '', string $prefixValue = '', string $postfixKey = '', string $postfixValue = ''): array
    {
        $output = [];
        foreach ($array as $key => $value) {
            $output[$prefixKey . $key . $postfixKey] = $prefixValue . $value . $postfixValue;
        }
        return $output;
    }

    /**
     * Add Prefix to all keys in arrays
     * @param array $array
     * @param string $prefix
     * @return array
     */
    public static function addKeyPrefix(array $array, string $prefix): array
    {
        return self::addPrefixPostfix($array, $prefix);
    }

    /**
     * Add Prefix to all values in arrays
     * @param array $array
     * @param string $prefix
     * @return array
     */
    public static function addValuePrefix(array $array, string $prefix = ''): array
    {
        return self::addPrefixPostfix($array, '', $prefix);
    }

    /**
     * Checks whether two arrays are equal
     * @param array $a
     * @param array $b
     * @return bool
     */
    public static function equal(array $a, array $b): bool
    {
        return (count($a) == count($b) && !array_diff($a, $b));
    }
}
