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
        $this->assertTrue(Regex::match('/login\.php/', 'somelogin.php', $matches, PREG_OFFSET_CAPTURE));
        $this->assertCount(1, $matches);
    }

    /** @test */
    public function match_string_with_pattern_and_get_all_matches()
    {
        $matches = [];
        $this->assertSame(Regex::matchAll('/(foo)(bar)(baz)/', 'foobarbaz', $matches, PREG_OFFSET_CAPTURE), 1);
        // print_r($matches);
        $this->assertCount(4, $matches);
    }

    /** @test */
    public function quote_string_with_special_regex_chars()
    {
        $this->assertEquals(Regex::quote('$40 for a g3/400', '/'), '\$40 for a g3\/400');
    }

    /** @test */
    public function replace_pattern_string_with_string()
    {
        $this->assertEquals(Regex::replace(
            '/(\w+) (\d+), (\d+)/i',
            '${1}1,$3',
            'April 15, 2003'
        ), 'April1,2003');
    }

    /** @test */
    public function replace_patterns_strings_using_indexed_arrays()
    {
        $string = 'The quick brown fox jumps over the lazy dog.';
        $patterns = array('/quick/', '/brown/', '/fox/');
        $replacements = array('bear', 'black', 'slow');
        $this->assertEquals(
            Regex::replace($patterns, $replacements, $string),
            'The bear black slow jumps over the lazy dog.'
        );
    }

    /** @test */
    public function replace_several_values_using_indexed_arrays()
    {
        $patterns = array(
            '/(19|20)(\d{2})-(\d{1,2})-(\d{1,2})/',
            '/^\s*{(\w+)}\s*=/'
        );
        $replacements = array('\3/\4/\1\2', '$\1 =');
        $string = '{startDate} = 1999-5-27';
        $this->assertEquals(
            Regex::replace($patterns, $replacements, $string),
            '$startDate = 5/27/1999'
        );
    }

    /** @test */
    public function replace_strings_with_callback()
    {
        $string = "Some numbers: one: 1; two: 2; three: 3 end";
        $ten = 10;
        $pattern = '/(\\d+)/';
        $callback = function ($match) use ($ten) {
            return (($match[0] + $ten));
        };
        $this->assertEquals(
            Regex::replace($pattern, $callback, $string),
            'Some numbers: one: 11; two: 12; three: 13 end'
        );
    }

    /** @test */
    public function replace_strings_with_callback_array()
    {
        $subject = 'Aaaaaa Bbb';
        $callbacks = [
            '~[a]+~i' => function ($match) {
                // return number of letters with letter ex 5A
                return strlen($match[0]) . substr($match[0], 0, 1);
            },
            '~[b]+~i' => function ($match) {
                return strlen($match[0]) . substr($match[0], 0, 1);
            }
        ];
        $this->assertEquals(
            Regex::replaceWithArrayCallbacks($callbacks, $subject),
            '6A 3B'
        );
    }

    /** @test */
    public function split_string_with_regex()
    {
        $result = array('php', 'language', 'programming');

        $this->assertEquals(
            Regex::split("/[\s,]+/", "php language, programming"),
            $result
        );
    }
}
