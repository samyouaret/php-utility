<?php

namespace PHPUtility\Session;

require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\Session\Utilities\FileSessionHandler;
use PHPUtility\Session\Utilities\Session;

$session = Session::getInstance();
$session->setHandler(new FileSessionHandler('/tmp/sessions'));
$session->start();

class FileSessionHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        global $session;
        $this->session = $session;
    }

    protected function tearDown(): void
    {
        if ($this->session->started()) {
            $this->session->destroy();
        }
    }


    /** @test */
    public function start_a_session_using_file_session_handler()
    {
        echo session_id() . "\n";
        echo session_name() . "\n";
        $this->assertTrue($this->session->started());
    }


    /** @test */
    public function ensure_session_instance_has_handler()
    {
        $this->assertInstanceOf(\SessionHandlerInterface::class,$this->session->getHandler());
    }

    /** @test */
    public function destroy_a_session_using_file_session_handler()
    {
        $this->session->destroy();
        $this->assertFalse($this->session->started());
    }

    /** @test */
    public function set_session_value_using_file_session_handler()
    {
        $this->session->username = "joe doe";
        $this->assertTrue(isset($this->session->username));
        $this->assertEquals($this->session->username, "joe doe");
    }

    /** @test */
    public function unset_session_value_using_file_session_handler()
    {
        $this->session->username = "joe doe";
        unset($this->session->username);
        $this->assertNull($this->session->username);
    }

    /** @test */
    public function set_session_value_access_as_array_using_file_session_handler()
    {
        $this->session['username'] = "joe doe";
        $this->assertTrue(isset($this->session['username']));
        $this->assertEquals($this->session['username'], "joe doe");
    }

    /** @test */
    public function session_is_instance_of_array_acess_using_file_session_handler()
    {
        $this->assertInstanceOf(\ArrayAccess::class, $this->session);
    }

    /** @test */
    public function unset_session_value_access_as_array_using_file_session_handler()
    {
        $this->session['username'] = "joe doe";
        unset($this->session['username']);
        $this->assertNull($this->session['username']);
    }
}
