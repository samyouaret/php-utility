<?php

namespace PHPUtility\Session\Utilities;

class FileSessionHandler implements \SessionHandlerInterface
{
    private $savePath;

    public function __construct(string $savePath)
    {
        $this->savePath = $savePath;
    }

    public function create_sid(): string
    {
        return session_create_id('phputility-');
    }

    function open($savePath, $sessionName)
    {
        $this->savePath = $savePath;
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777);
        }

        return true;
    }

    function close()
    {
        return true;
    }

    function read($id)
    {
        return (string) @file_get_contents("$this->savePath/sess_$id");
    }

    function write($id, $data)
    {
        return file_put_contents("$this->savePath/sess_$id", $data) === false ? false : true;
    }

    function destroy($id)
    {
        $file = "$this->savePath/sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    function gc($maxlifetime)
    {
        foreach (glob("$this->savePath/sess_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }
}
