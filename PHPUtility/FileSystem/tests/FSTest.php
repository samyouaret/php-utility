<?php

namespace PHPUtility\FileSystem;

require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\FileSystem\Utilities\FS;

class FSTest extends TestCase
{
    protected function setUp(): void
    {
        $this->dir =  __DIR__ . '/dumbs';
        $this->file =  __DIR__ . '/dumbs/file.txt';
        $this->nonEngFile =  __DIR__ . '/dumbs/ملف.txt';
    }

    /** @test */
    public function check_a_path_if_is_a_dir()
    {
        $this->assertTrue(FS::isDir($this->dir));
        $this->assertFalse(FS::isDir($this->file));
    }

    /** @test */
    public function check_a_path_if_is_a_file()
    {
        $this->assertFalse(FS::isFile($this->dir));
        $this->assertTrue(FS::isFile($this->file));
        $this->assertTrue(FS::isFile($this->nonEngFile));
    }

    /** @test */
    public function check_a_path_if_is_a_readable_file()
    {
        $this->assertTrue(FS::isReadable($this->dir));
        $this->assertTrue(FS::isReadable($this->file));
        $this->assertTrue(FS::isReadable($this->nonEngFile));
    }

    /** @test */
    public function check_a_path_if_is_a_writable_file()
    {
        $this->assertTrue(FS::isWritable($this->dir));
        $this->assertTrue(FS::isWritable($this->file));
        $this->assertTrue(FS::isWritable($this->nonEngFile));
    }

    /** @test */
    public function convert_bytes_to_symbol_string()
    {
        $this->assertEquals('10.00 MiB',FS::bytesToSymbol(1024 * 1024 * 10));
        $this->assertEquals('0.00 B',FS::bytesToSymbol(0));
    }
}
