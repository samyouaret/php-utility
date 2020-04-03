<?php

declare(strict_types=1);

namespace PHPUtility\System;


require dirname(".", 2) . '/bootstrap.php';

use PDO;
use PHPUnit\Framework\TestCase;
use PHPUtility\System\Utilities\Error;
use PHPUtility\System\Utilities\ErrorHandler;

class ErrorTest extends TestCase
{

    /** @test */
    public function get_last_error()
    {
        $error = new  Error();
        @fopen('somefile', 'r');
        $this->assertEquals(error_get_last(), $error->getLast());
    }

    /** @test */
    public function clear_last_error()
    {
        $error = new  Error();
        @fopen('somefile', 'r');
        $error->clearLast();
        $this->assertNull($error->getLast());
    }

    /** @test */
    public function get_last_error_as_string()
    {
        $error = new  Error();
        @fopen('somefile', 'r');
        $this->assertStringContainsString("WARNING: fopen(somefile)", $error->getLastMessage());
    }

    /** @test */
    public function set_error_handler()
    {
        $error = new  Error(new ErrorHandler);
        // $a = '' + 15;
        // $error->report(E_ALL, E_ERROR, E_CORE_ERROR, E_USER_ERROR, E_COMPILE_ERROR);
        // fopen('somefile', 'r');
        $pdo = new PDO('');
    }
}
