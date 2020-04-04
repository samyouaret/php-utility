<?php

namespace PHPUtility\System\Utilities;

use  PHPUtility\System\Utilities\ErrorHandlerInterface;

class ErrorHandler implements ErrorHandlerInterface
{
    public function handle(\Exception $e)
    {
        print_r($e);
    }
}
