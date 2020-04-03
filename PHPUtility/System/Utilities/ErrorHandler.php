<?php

namespace PHPUtility\System\Utilities;
// /
//  Setting for the PHP Error Handler
//  set_error_handler( call_back function or class );

//  Setting for the PHP Exceptions Error Handler
//  set_exception_handler(call_back function or class);

//  Setting for the PHP Fatal Error
//  register_shutdown_function(call_back function or class);
use  PHPUtility\System\Utilities\ErrorHandlerInterface;

class ErrorHandler implements ErrorHandlerInterface
{
    public function handle(\Exception $e)
    {
        var_dump($e);
    }

    public function getConfig(): array
    {
        return [
            'debug' => true,
        ];
    }
}
