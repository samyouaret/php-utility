<?php

namespace PHPUtility\FileSystem;

require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\FileSystem\Exceptions\InvalidPathException;
use PHPUtility\FileSystem\Utilities\Path;

class PathTest extends TestCase
{
    protected function setUp(): void
    {
        $this->dir =  __DIR__ . '/dumbs';
        $this->file =  __DIR__ . '/dumbs/file.txt';
        $this->nonEngFile =  __DIR__ . '/dumbs/ملف.txt';
    }

    /** @test */
    public function construct_path_with_valid_path()
    {
        $path = new Path($this->dir);
        $this->assertEquals($this->dir, $path->asString());
    }

    /** @test */
    public function construct_path_with_invalid_path()
    {
        $this->expectException(InvalidPathException::class);
        $path = new Path('non-existing/path');
    }

    /** @test */
    public function check_if_path_exists()
    {
        $this->assertTrue(Path::exists($this->dir));
    }

    /** @test */
    public function join_path()
    {
        $pathString = Path::join(__DIR__, 'dumbs');
        $path = new Path($pathString);
        $this->assertEquals($this->dir, $path->asString());
    }

    /** @test */
    public function get_basename_of_path()
    {
        $this->assertEquals('dumbs', Path::basename($this->dir));
    }

    /** @test */
    public function get_dirname_of_path()
    {
        $this->assertEquals(__DIR__, Path::dirname($this->dir));
    }

    /** @test */
    public function get_real_path()
    {
        $array =  explode(DIRECTORY_SEPARATOR, __DIR__);
        array_pop($array);
        $backwardDir = join(DIRECTORY_SEPARATOR, $array);
        $this->assertEquals($backwardDir, Path::real(__DIR__ . '/..'));
    }

    /** @test */
    public function parse_a_path()
    {
        $parsed = Path::parse("/www/htdocs/inc/lib.inc.php");
        $expected = [
            'dirname' => '/www/htdocs/inc',
            'basename' => 'lib.inc.php',
            'extension' => 'php',
            'filename' => 'lib.inc',
        ];
        $this->assertEquals($expected, $parsed);
    }
    /** @test */
    public function format_a_path()
    {
        $pathInfo = [
            'dirname' => '/www/htdocs/inc',
            'basename' => 'lib.inc.php',
            'extension' => 'php',
            'filename' => 'lib.inc',
        ];
        $path = Path::format($pathInfo);
        $this->assertEquals("/www/htdocs/inc/lib.inc.php", $path);
    }

    /** @test */
    public function format_a_path_with_no_basename()
    {
        $pathInfo = [
            'dirname' => '/www/htdocs/inc',
            'extension' => 'php',
            'filename' => 'lib.inc',
        ];
        $path = Path::format($pathInfo);
        $this->assertEquals("/www/htdocs/inc/lib.inc.php", $path);
    }
}
