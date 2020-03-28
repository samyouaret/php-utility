<?php

namespace PHPUtility\String\Utilities;

class Url
{
    /**
     * Decodes any %## encoding in the given string.
     *  Plus symbols ('+') are decoded to a space character. 
     */
    public static function encode(string $url): string
    {
        return urlencode($url);
    }

    public static function decode(string $url): string
    {
        return urldecode($url);
    }

    public static function parseQuery(string $url): array
    {
        $query =  parse_url($url, PHP_URL_QUERY);
        $result = [];
        foreach (explode("&", $query) as $value) {
            if ($param = explode("=", $value)) {
                $result[urldecode($param[0])] = urldecode($param[1]);
            }
        }
        return $result;
    }

    public static function buildQuery(iterable $data): string
    {
        return http_build_query($data);
    }

    public static function parse(string $url): array
    {
        return parse_url($url);
    }
}
