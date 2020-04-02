<?php

namespace PHPUtility\System\Utilities;

use PHPUtility\System\Exceptions\IniException;

class Ini
{
    public function set(string $var, string $value)
    {
        ini_set($var, $value);
    }

    public function setArray(array $options): array
    {
        $oldValues = [];
        foreach ($options as $option => $value) {
            $oldValues[$option] = ini_set($option, $value);
        }
        return $oldValues;
    }

    public function get(string $var): string
    {
        return ini_get($var);
    }

    public function getArray(array $options): array
    {
        $result = [];
        foreach ($options as $option) {
            $result[$option] = ini_get($option);
        }
        return $result;
    }

    public function restore(string $var)
    {
        ini_restore($var);
    }

    public function getAll(?string $extention = null, bool $details = true): array
    {
        if (!($options = @ini_get_all($extention, $details))) {
            $this->throwException($extention);
        }
        return $options;
    }

    public function getAllNoDetails(?string $extention = null): array
    {
        return $this->getAll($extention, false);
    }

    public function loadedFile(): string
    {
        return php_ini_loaded_file();
    }

    public function allFiles(): array
    {
        return explode(',', php_ini_scanned_files());
    }

    public static function parseFile(string $file, $mode = INI_SCANNER_TYPED): array
    {
        if (!file_exists($file)) {
            throw new IniException(" $file doesn't exist.  ", 1);
        }
        return parse_ini_file($file, true, $mode);
    }

    private function throwException($extention)
    {
        throw new IniException(" extension $extention doesn't exist.  ", 1);
    }
}
