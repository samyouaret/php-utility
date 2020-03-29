<?php

namespace PHPUtility\Session;

require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\Session\Utilities\Session;

$session = Session::getInstance();
$session->start();

class SessionTest extends TestCase
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
    public function start_a_session()
    {
        print_r($_SESSION);
        var_dump($this->session->started());
        var_dump(session_id());
        var_dump(session_status());
        $this->assertTrue($this->session->started());
    }

    /** @test */
    public function check_if_session_is_empty()
    {
        $this->assertTrue($this->session->empty());
    }

    /** @test */
    public function destroy_a_session()
    {
        $this->session->destroy();
        $this->assertFalse($this->session->started());
    }

    /** @test */
    public function set_session_value()
    {
        $this->session->username = "joe doe";
        $this->assertTrue(isset($this->session->username));
        $this->assertEquals($this->session->username, "joe doe");
    }

    /** @test */
    public function unset_session_value()
    {
        $this->session->username = "joe doe";
        unset($this->session->username);
        $this->assertNull($this->session->username);
    }

    /** @test */
    public function truncate_session_values()
    {
        $this->session->username = "joe doe";
        $this->session->age = 18;
        $this->session->truncate();
        $this->assertTrue($this->session->empty());
    }

    /** @test */
    public function set_session_value_access_as_array()
    {
        $this->session['username'] = "joe doe";
        $this->assertTrue(isset($this->session['username']));
        $this->assertEquals($this->session['username'], "joe doe");
    }

    /** @test */
    public function session_is_instance_of_array_acess()
    {
        $this->assertInstanceOf(\ArrayAccess::class, $this->session);
    }

    /** @test */
    public function unset_session_value_access_as_array()
    {
        $this->session['username'] = "joe doe";
        unset($this->session['username']);
        $this->assertNull($this->session['username']);
    }
}
