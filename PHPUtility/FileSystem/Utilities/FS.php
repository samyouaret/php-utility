<?php

namespace PHPUtility\FileSystem\Utilities;

class FS
{

    public static function exists(string $path)
    {
        return file_exists($path);
    }

    public static function isDir(string $path): bool
    {
        return ((fileperms("$path") & 0x4000) == 0x4000);
    }

    public static function isFile(string $path): bool
    {
        return is_file($path);
    }

    public static function isReadable(string $path): bool
    {
        return is_readable($path);
    }

    public static function isWritable(string $path): bool
    {
        return is_writable($path);
    }

    // public static function isLink(string $path): bool
    // {
    //     return is_link($path);
    // }

    // public static function makeLink(string $target, string $link): bool
    // {
    //     return symlink($target, $link);
    // }
}
