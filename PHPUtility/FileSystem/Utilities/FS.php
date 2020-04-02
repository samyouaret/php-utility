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

    public static function freeSpace(string $path): float
    {
        return diskfreespace($path);
    }

    public static function totalSpace(string $path): float
    {
        return disk_total_space($path);
    }

    public static function bytesToSymbol(float $bytes): string
    {
        $symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
        $exp = $bytes ? floor(log($bytes) / log(1024)) : 0;
        return sprintf('%.2f ' . $symbols[$exp], ($bytes / pow(1024, floor($exp))));
    }

    public static function rename($path, $newPath)
    {
        if (!file_exists($newPath))
            return rename($path, $newPath);
        return false;
    }

    public static function create(string $path)
    {
        if (file_exists($path)) {
            throw new \Exception("can't create <b>" . $path . "</b> File already exists");
        }
        var_dump($path);
        return \fopen($path, 'x');
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
