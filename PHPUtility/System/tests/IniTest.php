<?php

namespace PHPUtility\String;


require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\System\Exceptions\IniException;
use PHPUtility\System\Utilities\Ini;

class IniTest extends TestCase
{

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
        print_r($iniFiles);
        $this->assertEquals(php_ini_scanned_files(), join(',', $iniFiles));
    }
}
