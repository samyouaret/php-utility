<?php

namespace PHPUtility\FileSystem;

require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\FileSystem\Exceptions\DirectoryException;
use PHPUtility\FileSystem\Exceptions\DirectoryNotExistException;
use PHPUtility\FileSystem\Utilities\Directory;
use PHPUtility\FileSystem\Utilities\Path;

class DirectoryTest extends TestCase
{
    protected function setUp(): void
    {
        $this->dir = Path::join(__DIR__, 'dumbs');
        $this->subDir = Path::join($this->dir, 'subDir');
        $this->nonExistingDir = Path::join($this->dir, 'invalid-path');
    }

    protected function tearDown(): void
    {
    }

    /** @test */
    public function construct_dir_with_existing_dir()
    {
        $dir = new Directory($this->dir);
        $this->assertTrue($dir->open());
    }

    /** @test */
    public function construct_dir_with_unexisting_dir_throw_error()
    {
        $this->expectException(DirectoryNotExistException::class);
        $dir = new Directory($this->nonExistingDir);
        $dir->open();
    }

    /** @test */
    public function open_and_close_dir()
    {
        $dir = new Directory($this->dir);
        $this->assertTrue($dir->open());
        $this->assertTrue($dir->close());
    }

    /** @test */
    public function read_a_dir()
    {
        $dir = new Directory($this->dir);
        $dir->open();
        $expected = ['.', '..', 'file.txt', 'subDir', 'ملف.txt'];
        $result = [];
        while ($entry = $dir->read()) {
            $result[] = $entry;
        }
        $dir->close();
        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function create_a_dir()
    {
        $dir = new Directory($this->nonExistingDir);
        $dir->create();
        $this->assertDirectoryExists($this->nonExistingDir);
        rmdir($this->nonExistingDir);
    }

    /** @test */
    public function try_create_existing_dir_throw_directory_exception()
    {
        $this->expectException(DirectoryException::class);
        $dir = new Directory($this->dir);
        $dir->create();
    }

    /** @test */
    public function move_empty_dir_to_new_path()
    {
        $dir = new Directory($this->nonExistingDir);
        $dir->create();
        $newPath = Path::join($this->subDir, Path::basename($this->nonExistingDir));
        $dir->moveTo($newPath);
        $this->assertDirectoryExists($newPath);
        rmdir($newPath);
    }

    /** @test */
    public function move_non_empty_dir_to_new_path()
    {
        $dir = new Directory($this->nonExistingDir);
        $dir->create();
        //create new file
        $filePath = Path::join($this->nonExistingDir, 'newfile.txt');
        @fopen($filePath, 'w');
        $newPath = Path::join($this->subDir, Path::basename($this->nonExistingDir));
        $newfilePath = Path::join($this->subDir, Path::basename($newPath), 'newfile.txt');
        $dir->moveTo($newPath);
        $this->assertDirectoryExists($newPath);
        $this->assertFileExists($newfilePath);
        unlink($newfilePath);
        rmdir($newPath);
    }

    /** @test */
    public function rename_a_dir()
    {
        $dir = new Directory($this->nonExistingDir);
        $dir->create();
        $newPath = Path::join(Path::dirname($this->nonExistingDir), 'newdir');
        $dir->rename($newPath);
        $this->assertDirectoryExists($newPath);
        rmdir($newPath);
    }

    /** @test */
    public function delete_empty_dir()
    {
        $dir = new Directory($this->nonExistingDir);
        $dir->create();
        $this->assertDirectoryExists($this->nonExistingDir);
        $dir->delete();
        $this->assertDirectoryNotExists($this->nonExistingDir);
    }

    /** @test */
    public function delete_non_existing_dir_throw_error()
    {
        $this->expectException(DirectoryNotExistException::class);
        $dir = new Directory($this->nonExistingDir);
        $dir->delete();
    }

    /** @test */
    public function delete_non_empty_dir()
    {
        $dir = new Directory($this->nonExistingDir);
        $dir->create();
        //create new file
        @fopen(Path::join($this->nonExistingDir, 'newfile.txt'), 'w');
        @mkdir(Path::join($this->nonExistingDir, 'newDir'));
        $this->assertTrue($dir->forceDelete());
        $this->assertDirectoryNotExists($this->nonExistingDir);
    }
}
