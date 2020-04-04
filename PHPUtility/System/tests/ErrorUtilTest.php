<?php

declare(strict_types=1);

namespace PHPUtility\System;

require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\System\Utilities\ErrorUtil;

class ErrorUtilTest extends TestCase
{

    /** @test */
    public function get_last_error()
    {
        $error = new ErrorUtil();
        @fopen('somefile', 'r');
        $this->assertEquals(error_get_last(), $error->getLast());
    }

    /** @test */
    public function clear_last_error()
    {
        $error = new ErrorUtil();
        @fopen('somefile', 'r');
        $error->clearLast();
        $this->assertNull($error->getLast());
    }

    /** @test */
    public function get_last_error_as_string()
    {
        $error = new ErrorUtil();
        @fopen('somefile', 'r');
        $this->assertStringContainsString("WARNING: fopen(somefile)", $error->getLastMessage());
    }
}
