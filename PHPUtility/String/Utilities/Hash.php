<?php

namespace PHPUtility\String\Utilities;

class Hash
{
    public static function sha1(string $string): string
    {
        return sha1($string);
    }

    public static function sha1FromFile(string $filePath): string
    {
        return sha1_file($filePath);
    }

    public static function md5(string $string): string
    {
        return md5($string);
    }

    public static function md5FromFile(string $filePath): string
    {
        return md5_file($filePath);
    }

    public static function password(string $string, int $algo = 0): string
    {
        // use  CRYPT_BLOWFISH
        $algo = $algo ?? PASSWORD_BCRYPT;
        return password_hash($string, $algo);
    }

    public static function verifyPassword(string $string, string $hash): bool
    {
        return password_verify($string, $hash);
    }

    public static function crypt(string $string, string $salt): string
    {
        return crypt($string, $salt);
    }

    public static function equals(string $hash1, string $hash2): bool
    {
        return hash_equals($hash1, $hash2);
    }
}
