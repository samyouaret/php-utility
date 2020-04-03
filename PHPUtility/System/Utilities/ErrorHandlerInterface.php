<?php

namespace PHPUtility\System\Utilities;

interface ErrorHandlerInterface
{
    public function handle(\Exception $e);
    public function getConfig(): array;
}
