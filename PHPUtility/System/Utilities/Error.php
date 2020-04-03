<?php

namespace PHPUtility\System\Utilities;

ini_set("display_errors", "off");
// /
//  Setting for the PHP Error Handler
//  set_error_handler( call_back function or class );

//  Setting for the PHP Exceptions Error Handler
//  set_exception_handler(call_back function or class);

//  Setting for the PHP Fatal Error
//  register_shutdown_function(call_back function or class);
use  PHPUtility\System\Utilities\ErrorHandlerInterface;

class Error
{
    protected ErrorHandlerInterface $handler;
    protected bool $debug;

    public function __construct(?ErrorHandlerInterface $handler = null)
    {
        if ($handler) {
            $this->setHandler($handler);
        }
    }

    public function setHandler(ErrorHandlerInterface $handler)
    {
        $this->handler = $handler;
        register_shutdown_function(array($this, 'handleFatals'));
        set_error_handler(array($this, 'handleError'));
        set_exception_handler(array($handler, 'handle'));
        error_reporting(E_ALL);
        $this->debug = $handler->getConfig()['debug'] ?? true;
    }

    public function handleError(int $type, string $message, string $filename, int $line): bool
    {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        if (!(error_reporting() & $type)) {
            return false;
        }
        $this->handler->handle(new \ErrorException($message, 0, $type, $filename, $line));
        /* Don't execute PHP internal error handler */
        return true;
    }

    /**
     * Checks for a fatal error, work around for set_error_handler not working on fatal errors.
     */
    function handleFatals()
    {
        $error = error_get_last();
        if ($this->isFatal($error["type"]))
            $this->handleError($error["type"], $error["message"], $error["file"], $error["line"]);
    }


    public function getHandler()
    {
        return  $this->handler;
    }

    public function getLast(): ?array
    {
        return error_get_last();
    }

    public function getLastMessage(): string
    {
        $error = error_get_last();
        if (empty($error)) {
            return '';
        }
        $levelString = $this->errorLevelString($error['type']);
        return "$levelString: {$error['message']} in {$error['file']} on line {$error['line']}";
    }

    public function clearLast()
    {
        error_clear_last();
    }

    /**
     * @param void
     * @return int old reporting value
     */
    public function reportAll(): int
    {
        return \error_reporting(E_ALL);
    }

    /**
     * @param void
     * @return int old reporting value
     */
    public function reportOff(): int
    {
        return \error_reporting(0);
    }


    /**
     * @param void
     * @return int old reporting value
     */
    public function reportExcept(int ...$levels): int
    {
        $level = E_ALL & ~$this->reduceLevels($levels);
        return \error_reporting($level);
    }

    /**
     * @param void
     * @return int old reporting value
     */
    public function report(int ...$levels): int
    {
        $level = $this->reduceLevels($levels);
        return \error_reporting($level);
    }

    private function reduceLevels($levels)
    {
        return array_reduce($levels, fn ($carry, $level) => $carry | $level, 0);
    }

    public function errorLevelString(int $level): string
    {
        switch ($level) {
            case E_USER_WARNING:
            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
                return 'WARNING';
                break;
            case E_PARSE:
                return 'PARSE';
                break;
            case E_USER_ERROR:
            case E_CORE_ERROR:
            case E_ERROR:
            case E_COMPILE_ERROR:
                return 'FATAL';
                break;
            case E_RECOVERABLE_ERROR:
                return 'RECOVERABLE ERROR';
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                return 'NOTICE';
                break;
            case E_DEPRECATED:
                return 'DEPRECATED';
                break;
            default:
                return 'ERROR';
                break;
        }
    }
    public function isFatal(int $level): bool
    {
        return in_array($level, [E_ERROR, E_USER_ERROR, E_CORE_ERROR, E_COMPILE_ERROR]);
    }
}
