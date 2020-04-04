<?php

// declare(strict_types=1);

namespace PHPUtility\System;

require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\System\Utilities\Error;
use PHPUtility\System\Utilities\ErrorHandler;

class ErrorTest extends TestCase
{
    /** @test */
    public function set_error_handler()
    {
        $error = new Error([new ErrorHandler]);
        // $pdo = new \PDO('');
        $this->markTestIncomplete();
    }
}
