<?php

namespace PHPUtility\String;


require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\String\Utilities\Json;

class JsonTest extends TestCase
{
    protected function setUp(): void
    {
        $this->object = new \stdClass;
        $this->object->name = "adem";
        $this->object->age = 23;
        $this->json = '{"name":"adem","age":23}';
    }


    /** @test */
    public function parse_json_string()
    {
        $this->assertEquals(
            Json::parse($this->json),
            $this->object
        );
    }

    /** @test */
    public function parse_json_string_throw_exception()
    {
        $this->expectException(\JsonException::class);
        Json::parse($this->json . "syntax error");
    }

    /** @test */
    public function parse_json_string_result_as_array()
    {
        $array = (array) $this->object;
        $this->assertEquals(
            Json::parseAsArray($this->json),
            $array
        );
    }

    /** @test */
    public function stringify_object_or_array()
    {
        $this->assertEquals(
            Json::stringify($this->object),
            $this->json
        );
    }
}
