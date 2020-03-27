<?php

namespace PHPUtility\String;


require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\String\Utilities\Str;

class StrTest extends TestCase
{
    /** @test */
    public function substr_method()
    {
        $this->assertEquals(Str::substr('milano', 0, 3), 'mil');
    }

    /** @test */
    public function get_index_of_string_in_string()
    {
        $this->assertEquals(Str::indexOf('Hello world', "w"), 6);
    }

    /** @test */
    public function get_last_index_of_substring_in_string()
    {
        $this->assertEquals(Str::lastIndexOf('strange world,world.', "world"), 14);
    }

    /** @test */
    public function substring_method()
    {
        $this->assertEquals(Str::substring('milano', 0, 3), 'mil');
    }

    /** @test */
    public function  join_a_string_by_a_string()
    {
        $this->assertEquals(Str::join(' is ', ['milano', 'beautiful']), 'milano is beautiful');
    }

    /** @test */
    public function  split_a_string_by_a_string()
    {
        $this->assertEquals(Str::split(' ', 'milano is beautiful'), ['milano', 'is', 'beautiful']);
    }

    /** @test */
    public function  split_a_string_into_chunks()
    {
        $this->assertCount(3, Str::chunk('milano', 2));
    }

    /** @test */
    public function can_get_string_starting_from_given_string()
    {
        $this->assertEquals(Str::startFrom('@', 'adem@mail.com'), '@mail.com');
    }

    /** @test */
    public function can_get_string_starting_from_given_string_ignore_case()
    {
        $this->assertEquals(Str::startFrom('t', 'baTman', true), 'Tman');
    }

    /** @test */
    public function can_get_string_before_given_string()
    {
        $this->assertEquals(Str::before('@', 'adem@mail.com'), 'adem');
    }

    /** @test */
    public function can_get_string_before_given_string_ignore_case()
    {
        $this->assertEquals(Str::before('t', 'baTman', true), 'ba');
    }

    /** @test */
    public function convert_binary_string_to_hex()
    {
        $this->assertEquals(Str::binaryToHex("string"), '737472696e67');
    }

    /** @test */
    public function convert_hex_string_to_binary()
    {
        $this->assertEquals(Str::hexToBinary("737472696e67"), 'string');
    }

    /** @test */
    public function convert_binary_number_to_decimal()
    {
        $this->assertEquals(Str::binToDecimal("1010"), '10');
    }

    /** @test */
    public function get_spelling_string_for_a_word()
    {
        $this->assertEquals(Str::spell("programming"), 'PRKRMNK');
    }

    /** @test */
    public function get_char_at_given_index()
    {
        $this->assertEquals(Str::charAt("programming", 2), 'o');
    }

    /** @test */
    public function get_char_at_given_negative_index()
    {
        // negative index start index from -1 as first index
        $this->assertEquals(Str::charAt("programming", -2), 'n');
    }

    /** @test */
    public function get_char_at_invalid_index_throw_out_of_bound_exception()
    {
        $this->expectException(\OutOfBoundsException::class);
        Str::charAt("programming", -20);
    }

    /** @test */
    public function get_char_code_at_given_index()
    {
        $this->assertEquals(Str::charCodeAt("programming", 1), 114);
    }

    /** @test */
    public function get_char_code_at_negative_given_index()
    {
        $this->assertEquals(Str::charCodeAt("programming", -2), 110);
    }

    /** @test */
    public function get_string_char_from_char_code()
    {
        $this->assertEquals(Str::fromCharCode(110), 'n');
    }

    /** @test */
    public function string_starts_with_string()
    {
        $this->assertTrue(Str::startsWith('hello world', 'hello'));
    }

    /** @test */
    public function string_ends_with_string()
    {
        $this->assertTrue(Str::endsWith('hello world, hola world', "world"));
    }

    /** @test */
    public function string_includes_a_substring()
    {
        $this->assertTrue(Str::includes('hello world', "llo"));
    }

    /** @test */
    public function format_number()
    {
        $this->assertEquals(Str::formatNumber(1250.1574, 3, '.', ' '), '1 250.157');
    }

    /** @test */
    public function can_replace_substring_with_a_string()
    {
        $this->assertEquals(Str::replace("world", "london", "hello world"), 'hello london');
    }

    /** @test */
    public function can_replace_substring_with_a_string_search_is_array()
    {
        $this->assertEquals(
            Str::replace(["world",'mondo'], "london", "hello world,hola mondo"),
            'hello london,hola london'
        );
    }

    /** @test */
    public function can_replace_substring_with_a_string_where_search_and_replace_are_arrays()
    {
        $this->assertEquals(
            Str::replace(["world",'mondo'], ["newyork", 'london'], "hello world,hola mondo"),
            'hello newyork,hola london'
        );
    }
}
