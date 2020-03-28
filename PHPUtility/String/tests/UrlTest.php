<?php

namespace PHPUtility\String;


require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\String\Utilities\Url;

class UrlTest extends TestCase
{
    protected function setUp(): void
    {
        $this->url =  "http://username:password@hostname:9090/path?arg1=value1&arg2=value2#anchor";;
    }

    /** @test */
    public function encode_url_query()
    {
        $query = "green+and+red";
        $this->assertEquals(Url::encode($query), "green%2Band%2Bred");
    }

    /** @test */
    public function decode_url_query()
    {
        $query = "green%2Band%2Bred";
        $this->assertEquals(Url::decode($query), "green+and+red");
    }

    /** @test */
    public function parse_query_from_url_query()
    {
        $query =   array('arg1' => 'value1', 'arg2' => 'value2');
        $this->assertEquals(Url::parseQuery($this->url), $query);
    }

    /** @test */
    public function build_query_from_array_query()
    {
        $query = "arg1=value1&arg2=value2";
        $data =   array('arg1' => 'value1', 'arg2' => 'value2');
        $this->assertEquals(Url::buildQuery($data), $query);
    }

    /** @test */
    public function parse_url()
    {
        $parsed =   array(
            'scheme' => 'http',
            'host' => 'hostname',
            'port' => 9090,
            'user' => 'username',
            'pass' => 'password',
            'path' => '/path',
            'query' => 'arg1=value1&arg2=value2',
            'fragment' => 'anchor'
        );

        $this->assertEquals(Url::parse($this->url), $parsed);
    }
}
