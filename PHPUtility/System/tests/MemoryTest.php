<?php

namespace PHPUtility\String;


require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\System\Utilities\Memory;

class MemoryTest extends TestCase
{

    /** @test */
    public function get_peak_usage()
    {
        $this->assertEquals(memory_get_peak_usage(), Memory::peakUsage());
    }
    
    /** @test */
    public function get_real_peak_usage()
    {
        $this->assertEquals(memory_get_peak_usage(true), Memory::realPeakUsage());
    }

    /** @test */
    public function get_usage()
    {
        $this->assertEquals(memory_get_usage(), Memory::usage());
    }

    /** @test */
    public function get_real_usage()
    {
        $this->assertEquals(memory_get_usage(true), Memory::realUsage());
    }
}
