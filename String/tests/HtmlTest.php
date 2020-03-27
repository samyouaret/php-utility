<?php

namespace PHPUtility\String;


require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\String\Utilities\Html;

class HtmlTest extends TestCase
{
    /** @test */
    public function encode_html_string()
    {
        $this->assertEquals(
            Html::encode('<h1>"hello \'world"</h1>'),
            // replace both single ' and double '
            "&lt;h1&gt;&quot;hello &apos;world&quot;&lt;&sol;h1&gt;"
        );
    }

    /** @test */
    public function decode_html_string()
    {
        $this->assertEquals(Html::decode('&lt;h1&gt;hello world&lt;&sol;h1&gt;'), '<h1>hello world</h1>');
    }
    /** @test */
    public function encode_only_special_html_chars()
    {
        $this->assertEquals(
            Html::encodeOnly('<h1>"hello \'world"</h1>'),
            // replace both single ' and double '
            "&lt;h1&gt;&quot;hello &apos;world&quot;&lt;/h1&gt;"
        );
    }

    /** @test */
    public function decode_only_special_html_chars()
    {
        $this->assertEquals(Html::decodeOnly('&lt;h1&gt;hello world&lt;/h1&gt;'), '<h1>hello world</h1>');
    }

    /** @test */
    public function replace_new_line_with_line_break()
    {
        $this->assertEquals(
            Html::nlToBr("hello,world\n,hola mondo\r\n"),
            'hello,world<br />,hola mondo<br />'
        );
    }

    /** @test */
    public function replace_line_break_with_new_line()
    {
        // echo Html::brToNl("'hello,<br />world,hola<br />mondo");
        $this->assertEquals(
            Html::brToNl("hello,world<br />,hola mondo<br />"),
            "hello,world\n,hola mondo\n"
        );
    }

    /** @test */
    public function strip_html_tags()
    {
        // echo Html::brToNl("'hello,<br />world,hola<br />mondo");
        $this->assertEquals(
            Html::stripTags("<b>hello,world<b><br />,hola mondo<br />", ['b']),
            "<b>hello,world<b>,hola mondo"
        );
    }
}
