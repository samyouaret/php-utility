<?php

namespace PHPUtility\System\Utilities;

use  PHPUtility\System\Utilities\ErrorHandlerInterface;

class Error
{
    protected array $handlers = [];

    public function __construct(array $handlers = [])
    {
        foreach ($handlers as $handler) {
            $this->addHandler($handler);
        }
        error_reporting(E_ALL);
        $this->register();
    }

    public function addHandler(ErrorHandlerInterface $handler)
    {
        array_push($this->handlers, $handler);
    }

    public function register()
    {
        // We want the formatters we register to handle the errors.
        ini_set('display_errors', 'off');
        register_shutdown_function(array($this, 'handleFatals'));
        set_error_handler(array($this, 'handleError'));
        set_exception_handler(array($this, 'handleException'));
    }

    /**
     * Checks for a fatal error, work around for set_error_handler not working on fatal errors.
     */
    function handleFatals()
    {
        $errorUtil = new ErrorUtil;
        $error = $errorUtil->getLast();
        if ($error && $errorUtil->isFatal($error["type"]))
            $this->handleError($error["type"], $error["message"], $error["file"], $error["line"]);
    }

    public function handleError(int $type, string $message, string $filename, int $line): bool
    {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        if (!(error_reporting() & $type)) {
            return false;
        }
        $this->handleException(new \ErrorException($message, 0, $type, $filename, $line));
        /* Don't execute PHP internal error handler */
        return true;
    }

    public function handleException(\Exception $e)
    {
        foreach ($this->handlers as $handler) {
            $handler->handle($e);
        }
    }

    public function getHandlers()
    {
        return  $this->handlers;
    }

    public function clearHandlers()
    {
        $this->handlers = [];
    }

    public function unregister()
    {
        restore_error_handler();
        restore_exception_handler();
    }
}
