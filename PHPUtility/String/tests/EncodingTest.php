<?php

namespace PHPUtility\String;


require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\String\Utilities\Encoding;

class EncodingTest extends TestCase
{
 
    /** @test */
    public function encode_base_64_string()
    {
        $string = "This is an encoded string";
        $this->assertEquals(Encoding::encodeBase64($string), "VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw==");
    }

    /** @test */
    public function decode_base_64_string()
    {
        $string = "VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw==";
        $this->assertEquals(Encoding::decodeBase64($string), "This is an encoded string");
    }

    /** @test */
    public function encode_uuencode_string()
    {
        $string = "This is an encoded string";
        $this->assertEquals(Encoding::uuencode($string), "95&AI<R!I<R!A;B!E;F-O9&5D('-T<FEN9P``\n`\n");
    }

    /** @test */
    public function decode_uuencode_string()
    {
        $string = "95&AI<R!I<R!A;B!E;F-O9&5D('-T<FEN9P``\n`\n";
        $this->assertEquals(Encoding::uudecode($string), "This is an encoded string");
    }
}
