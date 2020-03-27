<?php

namespace PHPUtility\String\Utilities;

class Str
{

    public static function lower(string $str): string
    {
        return strtolower($str);
    }

    public static function upper(string $str): string
    {
        return strtoupper($str);
    }

    public static function length(string $str): int
    {
        return strlen($str);
    }

    public static function indexOf(string $haystack, $needle, int $offset = 0): int
    {
        $pos = strpos($haystack, $needle, $offset);
        return  $pos === false ? -1 : $pos;
    }

    public static function lastIndexOf(string $haystack, $needle, int $offset = 0): int
    {
        $pos = strrpos($haystack, $needle, $offset);
        return  $pos === false ? -1 : $pos;
    }

    public static function substr(string $str, int $start, $length = null): string
    {
        return \substr($str, $start, $length);
    }

    public static function substring(string $str, int $start, $end = null): string
    {
        return \substr($str, $start,  $end - $start);
    }

    public static function join(string $joiner, array $array): string
    {
        return \implode($joiner, $array);
    }

    public static function split(string $splitter, string $str): array
    {
        return \explode($splitter, $str);
    }

    public static function chunk(string $str, int $chunk_length = 1): array
    {
        return \str_split($str, $chunk_length);
    }

    public static function startFrom(string $from, string $str, bool $case = false): string
    {
        return $case == false ? \strstr($str, $from) : \stristr($str, $from);
    }

    public static function before(string $from, string $str, bool $case = false): string
    {
        return $case == false ? \strstr($str, $from, true) : \stristr($str, $from, true);
    }

    public static function binaryToHex(string $binary): string
    {
        return bin2hex($binary);
    }

    public static function hexToBinary(string $hex): string
    {
        return hex2bin($hex);
    }

    public static function binToDecimal(string $binaryStr): string
    {
        return bindec($binaryStr);
    }

    public static function spell(string $word): string
    {
        return metaphone($word);
    }

    public static function charAt(string $str, int $index): string
    {
        if (abs($index) >= strlen($str)) {
            throw new \OutOfBoundsException("invalid index $index given", 1);
        }
        return $str[$index];
    }

    public static function charCodeAt(string $str, int $index): string
    {
        return ord(self::charAt($str, $index));
    }

    // multibyte encoding like UTF-8 or UTF-16 are not accepted
    public static function fromCharCode(int $charCode): string
    {
        return chr($charCode);
    }

    public static function startsWith(string $str, string $startsWith): bool
    {
        return self::indexOf($str, $startsWith) == 0;
    }

    public static function endsWith(string $str, string $endsWith): bool
    {
        if (($lastPos = strrpos($str, $endsWith)) === false) {
            return false;
        }
        return $lastPos  + strlen($endsWith) === strlen($str);
    }

    public static function trim(string $str, $skip = null): string
    {
        return trim($str, $skip);
    }

    public static function rtrim(string $str, $skip = null): string
    {
        return rtrim($str, $skip);
    }

    public static function ltrim(string $str, $skip = null): string
    {
        return ltrim($str, $skip);
    }

    public static function includes(string $str, string $substr, int $start = 0): bool
    {
        return self::indexOf($str, $substr, $start) > -1;
    }

    public static function  formatNumber(float $number, int $decimals = 0, string $dec_point = ".", string $thousands_sep = ","): string
    {
        return  number_format($number, $decimals, $dec_point, $thousands_sep);
    }

    /**
     * If search and replace are arrays, then str_replace() takes a value from each
     *  array and uses them to search and replace on subject. If replace has fewer 
     * values than search, then an empty string is used for the rest of replacement values. 
     * If search is an array and replace is a string, then this replacement string
     *  is used for every value of search. The converse would not make sense, though. 
     */
    public static function  replace($search, $replace, $subject): string
    {
        return  str_replace($search, $replace, $subject);
    }

    public static function  replaceIgnoreCase($search, $replace, $subject): string
    {
        return  str_ireplace($search, $replace, $subject);
    }
}
