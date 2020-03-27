<?php

namespace PHPUtility\String\Utilities;

class Html
{
    public static function encode(string $string, int $flags = ENT_QUOTES | ENT_HTML5, string $encoding = "UTF-8"): string
    {
        return htmlentities($string, $flags, $encoding);
    }

    public static function decode(string $string, int $flags = ENT_QUOTES | ENT_HTML5, string $encoding = "UTF-8"): string
    {
        return html_entity_decode($string, $flags, $encoding);
    }

    public static function encodeOnly(string $string, int $flags = ENT_QUOTES | ENT_HTML5, string $encoding = "UTF-8"): string
    {
        return htmlspecialchars($string, $flags, $encoding);
    }

    public static function decodeOnly(string $string, int $flags = ENT_QUOTES | ENT_HTML5): string
    {
        return htmlspecialchars_decode($string, $flags);
    }

    public static function nlToBr(string $string): string
    {
        return str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
    }

    public static function brToNl(string $string): string
    {
        return str_replace(array("<br />", "<br>", "<br/>"), "\n", $string);
    }

    public static function stripTags(string $string, array $allowable = null): string
    {
        return strip_tags($string, $allowable);
    }
}
