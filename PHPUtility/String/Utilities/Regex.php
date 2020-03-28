<?php

namespace PHPUtility\String\Utilities;

class Regex
{
    public static function match(string $pattern, string $subject, array &$matches = null, int $flags = 0, int $offset = 0): bool
    {
        return preg_match($pattern, $subject, $matches, $flags, $offset) != false;
    }

    public static function matchAll(string $pattern, string $subject, array &$matches = null, int $flags = PREG_PATTERN_ORDER, int $offset = 0): int
    {
        return preg_match_all($pattern, $subject, $matches, $flags, $offset);
    }

    public static function quote(string $str, string $delimiter = NULL): string
    {
        return preg_quote($str, $delimiter);
    }

    public static function replace($pattern, $replacement, $subject, int $limit = -1, int &$count = null)
    {
        if (is_callable($replacement)) {
            return preg_replace_callback($pattern, $replacement, $subject, $limit, $count);
        }
        return preg_replace($pattern, $replacement, $subject, $limit, $count);
    }

    public static function replaceWithArrayCallbacks(array $pattern_callbacks, $subject, int $limit = -1, int &$count = null)
    {
        return preg_replace_callback_array($pattern_callbacks, $subject, $limit, $count);
    }

    public static function split(string $pattern, string $subject, int $limit = -1, int $flags = 0): array
    {
        return preg_split($pattern, $subject, $limit, $flags);
    }
}
