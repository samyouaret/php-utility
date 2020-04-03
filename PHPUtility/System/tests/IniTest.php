<?php

namespace PHPUtility\System;


require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\System\Exceptions\IniException;
use PHPUtility\System\Utilities\Ini;

class IniTest extends TestCase
{
    protected function setUp(): void
    {
        $this->config = [
            'first_section' => array(
                'one' => 1,
                'five' => 5,
                'animal' => 'BIRD'
            ),
            'second_section' => array(
                'path' => '/usr/local/bin',
                'URL' => 'http://www.example.com/~username'
            ),
            'third_section' => array(
                'phpversion' => [5.0, 5.1, 5.2, 5.3],
                'urls' => array(
                    'svn' => 'http://svn.php.net',
                    'git' => 'http://git.php.net'
                )
            )
        ];
    }

    /** @test */
    public function get_all_method()
    {
        $ini = new Ini();
        $this->assertEquals(ini_get_all(), $ini->getAll());
    }

    /** @test */
    public function get_all_with_non_existing_extension_throw_error()
    {
        $this->expectException(IniException::class);
        $ini = new Ini();
        $ini->getAll('undefined-ext');
    }

    /** @test */
    public function get_all_with_extension_name_method()
    {
        $ini = new Ini();
        $this->assertEquals(ini_get_all('pcre'), $ini->getAll('pcre'));
    }

    /** @test */
    public function get_all_no_details_with_extension_name_method()
    {
        $ini = new Ini();
        $this->assertEquals(ini_get_all('pcre', false), $ini->getAllNoDetails('pcre'));
    }

    /** @test */
    public function set_ini_options_with_array()
    {
        $ini = new Ini();
        $old = $ini->setArray([
            'display_errors' => 1,
            'memory_limit' => '100M',
        ]);
        $this->assertEquals(1, ini_get('display_errors'));
        $this->assertEquals('100M', ini_get('memory_limit'));
    }

    /** @test */
    public function get_subset_of_ini_options_as_array()
    {
        $ini = new Ini();
        $options = [
            'display_errors' => 1,
            'memory_limit' => '100M',
        ];
        $ini->setArray($options);
        $result = $ini->getArray(['display_errors', 'memory_limit']);
        $this->assertEquals(1, $result['display_errors']);
        $this->assertEquals('100M', $result['memory_limit']);
    }

    /** @test */
    public function get_all_ini_loaded_files()
    {
        $ini = new Ini();
        $iniFiles = $ini->allFiles();
        $this->assertEquals(php_ini_scanned_files(), join(',', $iniFiles));
    }

    /** @test */
    public function parse_ini_file()
    {
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'dumbs/config.ini.php';
        $this->assertEquals($this->config, Ini::parseFile($file));
    }

    /** @test */
    public function parse_ini_file_throw_error_when_file_does_not_exist()
    {
        $this->expectException(IniException::class);
        $this->assertEquals($this->config, Ini::parseFile('invalid-path/file.ini'));
    }

    /** @test */
    public function parse_ini_string()
    {
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'dumbs/config.ini.php';
        $content = file_get_contents($file);
        $this->assertEquals($this->config, Ini::parse($content));
    }
}
