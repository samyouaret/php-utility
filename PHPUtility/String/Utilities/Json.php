<?php

namespace PHPUtility\String\Utilities;

use stdClass;

class Json
{
    public static function parse(string $string, int $options = JSON_THROW_ON_ERROR, int $depth = 512): stdClass
    {
        return json_decode($string, false, $depth, $options);
    }

    public static function parseAsArray(string $string, int $options = JSON_THROW_ON_ERROR, int $depth = 512): array
    {
        return json_decode($string, true, $depth, $options);
    }

    public static function stringify($value, int $options = JSON_THROW_ON_ERROR, int $depth = 512): string
    {
        return json_encode($value, $options, $depth);
    }

    public static function getErrorString(): string
    {
        return json_last_error_msg();
    }

    public static function getErrorCode(): int
    {
        return json_last_error();
    }
}
