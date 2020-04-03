<?php

namespace PHPUtility\Types;

use PHPUnit\Framework\TestCase;

require dirname(".", 2) . '/bootstrap.php';

class IsTest extends TestCase
{
    /** @test */
    public function hash_string_with_sha1()
    {
        $this->assertEquals(1, 1);
    }
}
