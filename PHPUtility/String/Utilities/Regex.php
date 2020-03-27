<?php

namespace PHPUtility\String\Utilities;

class Regex
{
    public static function match(string $pattern, string $subject, array &$matches = null): bool
    {
        return preg_match($pattern, $subject, $matches) != false;
    }
}
