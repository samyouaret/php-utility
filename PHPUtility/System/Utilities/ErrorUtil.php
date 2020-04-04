<?php

namespace PHPUtility\System\Utilities;


class ErrorUtil
{

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
                return 'CATCHABLE ERROR';
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
