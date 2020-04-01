<?php

namespace PHPUtility\FileSystem;

require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\FileSystem\Exceptions\DirectoryDoesNotExistException;
use PHPUtility\FileSystem\Utilities\Path;

class DirectoryTest extends TestCase
{
    protected function setUp(): void
    {
        $this->subDir = Path::join($this->dir, 'subDir');
        $this->nonExistingDir = Path::join($this->dir, 'invalid-path');
        $this->nonExistingFile = Path::join($this->dir, 'invalid-path', 'newfile.txt');
    }

    protected function tearDown(): void
    {
    }

    /** @test */
    public function construct_dir_with_existing_dir()
    {
    }
}
