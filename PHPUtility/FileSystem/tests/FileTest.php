<?php

namespace PHPUtility\FileSystem;

require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\FileSystem\Exceptions\DirectoryDoesNotExistException;
use PHPUtility\FileSystem\Exceptions\FileDoesNotExistException;
use PHPUtility\FileSystem\Exceptions\InvalidModeException;
use PHPUtility\FileSystem\Exceptions\PerformOperationOnClosedFileException;
use PHPUtility\FileSystem\Utilities\File;
use PHPUtility\FileSystem\Utilities\Path;

class FileTest extends TestCase
{
    protected function setUp(): void
    {
        $this->dir = Path::join(__DIR__, 'dumbs');
        $this->file = Path::join($this->dir, 'file.txt');
        $this->nonEngFile = Path::join($this->dir, 'ملف.txt');
        $this->newFile = Path::join($this->dir, 'newfile.txt');
        $this->nonExistingDir = Path::join($this->dir, 'invalid-path');
        $this->nonExistingFile = Path::join($this->dir, 'invalid-path', 'newfile.txt');
    }

    protected function tearDown(): void
    {
        @unlink($this->newFile);
        @unlink($this->nonExistingDir);
    }


    /** @test */
    public function construct_file_with_existing_and_mode()
    {
        $file = new File($this->file, File::READ_ONLY);
        $this->assertTrue(true);
    }

    /** @test */
    public function construct_file_with_non_existing_path_for_read()
    {
        $this->expectException(FileDoesNotExistException::class);
        $file = new File($this->nonExistingFile, File::READ_ONLY);
    }

    /** @test */
    public function construct_file_with_non_existing_path_for_write_should_not_thorw_an_error()
    {
        $file = new File($this->nonExistingFile, File::WRITE_ONLY);
        $this->assertTrue(true);
    }

    /** @test */
    public function open_file_with_existing_path_for_read()
    {
        $file = new File($this->file, File::READ_ONLY);
        $this->assertTrue($file->open());
    }

    /** @test */
    public function open_file_with_non_existing_file_for_write_create_a_new_file()
    {
        $file = new File($this->newFile, File::WRITE_ONLY);
        // create new file
        $this->assertTrue($file->open());
    }

    /** @test */
    public function open_file_with_non_existing_directory_for_write_create_a_new_file()
    {
        $file = new File($this->nonExistingDir, File::WRITE_ONLY);
        $this->assertTrue($file->open());
    }

    /** @test */
    public function open_file_with_non_existing_file_in_non_existing_dir_for_write_throw_error()
    {
        $this->expectException(DirectoryDoesNotExistException::class);
        $file = new File($this->nonExistingFile, File::WRITE_ONLY);
        $this->assertTrue($file->open());
    }

    /** @test */
    public function open_and_close_file()
    {
        $file = new File($this->file, File::READ_ONLY);
        $file->open();
        $this->assertTrue($file->close());
        $this->assertFalse($file->isOpen());
    }

    /** @test */
    public function open_and_read_file()
    {
        $file = new File($this->file, File::READ_ONLY);
        $file->open();
        $content = file_get_contents($this->file);
        $this->assertEquals($content, $file->read());
        $file->close();
    }


    /** @test */
    public function read_file_before_open_it_throw_excpetion()
    {
        $this->expectException(PerformOperationOnClosedFileException::class);
        $file = new File($this->newFile, File::WRITE_ONLY);
        $file->read();
    }

    /** @test */
    public function open_and_read_file_chunk_per_chunk()
    {
        $file = new File($this->file, File::READ_ONLY);
        $file->open();
        $content = file_get_contents($this->file);
        $length = 100;
        $result = '';
        // this the write way, oef sometimes does not work as needed
        while (!$file->eof() && $chunk = $file->read($length)) {
            $result .= $chunk;
        }
        $this->assertEquals($content, $result);
        $file->close();
    }

    /** @test */
    public function read_throw_excpetion_with_non_read_mode()
    {
        $this->expectException(InvalidModeException::class);
        $file = new File($this->newFile, File::WRITE_ONLY);
        $file->open();
        $file->read();
    }

    /** @test */
    public function open_and_read_file_in_async_mode_using_generators()
    {
        $file = new File($this->file, File::READ_ONLY);
        $file->open();
        $Expectedcontent = file_get_contents($this->file);
        $result = '';
        foreach ($file->readAsync(100) as $read => $chunk) {
            $result .= $chunk;
        }
        $this->assertEquals($Expectedcontent, $result);
        $file->close();
    }

    /** @test */
    public function open_and_read_file_using_content_method()
    {
        $file = new File($this->file, File::READ_ONLY);
        $file->open();
        $content = file_get_contents($this->file);
        $this->assertEquals($content, $file->content());
        $file->close();
    }


    /** @test */
    public function open_and_read_file_line_by_line()
    {
        $file = new File($this->file, File::READ_ONLY);
        $file->open();
        $content = file_get_contents($this->file);
        $result = '';
        while (!$file->eof() && $line = $file->readLine()) {
            $result .= $line;
        }
        $this->assertEquals($content, $result);
        $file->close();
    }


    /** @test */
    public function open_and_read_file_in_async_mode_line_per_line()
    {
        $file = new File($this->file, File::READ_ONLY);
        $file->open();
        $Expectedcontent = file_get_contents($this->file);
        $result = '';
        // each line will be a 120 byte length
        foreach ($file->readLineAsync(120) as $read => $chunk) {
            $result .= $chunk;
        }
        $this->assertEquals($Expectedcontent, $result);
        $file->close();
    }


    /** @test */
    public function open_and_read_file_char_by_char()
    {
        $file = new File($this->file, File::READ_ONLY);
        $file->open();
        $content = file_get_contents($this->file);
        $result = '';
        while (!$file->eof() && $char = $file->readChar()) {
            $result .= $char;
        }
        $this->assertEquals($content, $result);
        $file->close();
    }

    /** @test */
    public function open_and_read_file_in_async_mode_char_by_char()
    {
        $file = new File($this->file, File::READ_ONLY);
        $file->open();
        $Expectedcontent = file_get_contents($this->file);
        $result = '';
        foreach ($file->readCharAsync() as $char) {
            $result .= $char;
        }
        $this->assertEquals($Expectedcontent, $result);
        $file->close();
    }
}
