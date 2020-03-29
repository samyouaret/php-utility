<?php

namespace PHPUtility\Session\Utilities;

class Session implements \ArrayAccess
{
    private \SessionHandlerInterface $handler;
    // THE only instance of the class
    private static $instance;

    private function __construct()
    {
        session_name("PHPUtility");
    }

    /**
     * The session is automatically initialized if it wasn't.
     *   
     * @return object instance of 'Session'
     **/
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
            $_SESSION = [];
        }
        return self::$instance;
    }

    /**
     * (Re)starts the session.
     *   
     * @return bool true if the session has been initialized, else false.
     **/
    public function start(): bool
    {
        return  $this->started() ? true : session_start();
    }

    public function started(): bool
    {
        return  session_status() === PHP_SESSION_ACTIVE;
    }

    public function empty(): bool
    {
        return !isset($_SESSION) || empty($_SESSION);
    }

    /**
     *  Destroys the current session.
     *   
     *   @return  bool true is session has been deleted, else false.
     **/
    public function destroy()
    {
        if ($this->started()) {
            $bool = session_destroy();
            unset($_SESSION);
            return $bool;
        }
        return false;
    }

    public function truncate(): void
    {
        $_SESSION = [];
    }
    /**
     *  Stores datas in the session.
     *  Example: $instance->foo = 'bar';
     * 
     *  @param    name    Name of the datas.
     *  @param    value    Your datas.
     *  @return    void
     **/
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     *    @param    name    Name of the datas to get.
     *    @return    mixed    Datas stored in session.
     **/
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     *    @param    name    Name of the datas to get.
     *    @return    mixed    Datas stored in session.
     **/
    public function get($name)
    {
        return $this->offsetGet($name);
    }

    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }

    public function __unset($name)
    {
        unset($_SESSION[$name]);
    }

    /* Methods of ArrayAccess */
    public function offsetExists($offset): bool
    {
        return isset($_SESSION[$offset]);
    }

    public function offsetGet($offset)
    {
        if (isset($_SESSION[$offset])) {
            return $_SESSION[$offset];
        }
    }

    public function offsetSet($offset,  $value): void
    {
        $_SESSION[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($_SESSION[$offset]);
    }

    public function setHandler(\SessionHandlerInterface $handler)
    {
        $this->handler = $handler;
        session_set_save_handler(
            array($this->handler, 'open'),
            array($this->handler, 'close'),
            array($this->handler, 'read'),
            array($this->handler, 'write'),
            array($this->handler, 'destroy'),
            array($this->handler, 'gc')
        );
        // prevents unexpected effects when using objects as save handlers
        register_shutdown_function('session_write_close');
    }

    public function getHandler(): \SessionHandlerInterface
    {
        return $this->handler;
    }

    public function clearHandler()
    {
        unset($this->handler);
        $this->handler = null;
    }
}
