<?php

namespace PHPUtility\FileSystem\Utilities;

use PHPUtility\FileSystem\Exceptions\InvalidPathException;

class Path
{
    private string $path;

    public function __construct(string $path)
    {
        clearstatcache();
        $this->ensureValid($path);
        $this->path = $path;
    }

    public function __toString()
    {
        return $this->asString();
    }

    public function asString()
    {
        return $this->path;
    }

    public static function exists(string $path)
    {
        return file_exists($path);
    }

    public static function basename(string $path, int $levels = 1)
    {
        return basename($path, $levels);
    }

    public static function dirname(string $path, int $levels = 1)
    {
        return dirname($path, $levels);
    }

    public static function real(string $path)
    {
        return realpath($path);
    }

    public static function join(...$parts)
    {
        return join(DIRECTORY_SEPARATOR, $parts);
    }

    public static function parse(string $path)
    {
        return pathinfo($path);
    }

    public static function format(array $info)
    {
        if (!key_exists('dirname', $info)) {
            throw new \Exception('no dirname option is given', 1);
        }
        if (key_exists('basename', $info)) {
            return  Path::join($info['dirname'], $info['basename']);
        }
        if (!key_exists('filename', $info) || !key_exists('extension', $info)) {
            throw new \Exception('no basename option is given nor extension and filename', 1);
        }
        return  Path::join($info['dirname'], $info['filename'] . '.' . $info['extension']);
    }

    private function ensureValid(string $path)
    {
        if (!file_exists($path)) {
            throw new InvalidPathException("Invalid Path passed", 1);
        }
    }
}
