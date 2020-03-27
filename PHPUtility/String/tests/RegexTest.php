<?php

namespace PHPUtility\String;


require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\String\Utilities\Regex;

class RegexTest extends TestCase
{
    /** @test */
    public function match_string_with_pattern()
    {
        $matches = [];
        $this->assertTrue(Regex::match('/.+\.php/', 'file.php', $matches));
        $this->assertCount(1, $matches);
    }
}
