<?php

namespace PHPUtility\String\Utilities;

class Encoding
{
    public static function encodeBase64(string $data): string
    {
        return base64_encode($data);
    }

    public static function decodeBase64(string $data): string
    {
        return base64_decode($data);
    }

    public static function uuencode(string $data): string
    {
        return convert_uuencode($data);
    }

    public static function uudecode(string $data): string
    {
        return convert_uudecode($data);
    }
}
