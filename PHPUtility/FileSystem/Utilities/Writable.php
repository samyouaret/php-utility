<?php

namespace PHPUtility\FileSystem\Utilities;

interface Writable
{
    public function write(string $string): int;
}
