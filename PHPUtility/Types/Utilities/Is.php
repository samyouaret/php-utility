<?php

namespace PHPUtility\Types\Utilities;

class Is
{
    public static function type($var, string $type): bool
    {
        return gettype($var) === $type;
    }

    public static function numeric($var): bool
    {
        return is_numeric($var);
    }

    public static function interger($var): bool
    {
        return is_int($var);
    }

    public static function float($var): bool
    {
        return is_float($var);
    }

    public static function bool($var): bool
    {
        return is_bool($var);
    }

    public static function null($var): bool
    {
        return is_null($var);
    }

    public static function empty($var): bool
    {
        return empty($var);
    }

    public static function set($var): bool
    {
        return isset($var);
    }

    public static function long($var): bool
    {
        return is_long($var);
    }

    public static function nan(float $var): bool
    {
        return is_nan($var);
    }

    public static function finite(float $var): bool
    {
        return is_finite($var);
    }

    public static function infinite(float $var): bool
    {
        return is_infinite($var);
    }

    public static function array($var): bool
    {
        return is_array($var);
    }

    public static function object($var): bool
    {
        return is_object($var);
    }

    public static function resource($var): bool
    {
        return is_resource($var);
    }

    public static function resourceOf($var, $type): bool
    {
        return get_resource_type($var) === $type;
    }

    public static function callable($var): bool
    {
        return is_callable($var);
    }

    public static function string($var): bool
    {
        return is_string($var);
    }

    public static function scalar($var): bool
    {
        return is_scalar($var);
    }

    // ctype methods
    public static function alnum(string $string): bool
    {
        return ctype_alnum($string);
    }

    public static function alpha(string $string): bool
    {
        return ctype_alpha($string);
    }

    public static function lower(string $string): bool
    {
        return ctype_lower($string);
    }

    public static function upper(string $string): bool
    {
        return ctype_upper($string);
    }

    public static function hex(string $string): bool
    {
        return ctype_xdigit($string);
    }

    public static function printable(string $string): bool
    {
        return ctype_print($string);
    }
    
    // space are not included in notAlnum chars
    public static function notAlnum(string $string): bool
    {
        return ctype_punct($string);
    }
    
    // space are not included in notAlnum chars
    public static function space(string $string): bool
    {
        return ctype_space($string);
    }
}
