<?php

declare(strict_types=1);

namespace PHPUtility\System;

require dirname(".", 2) . '/bootstrap.php';

use PHPUtility\System\Utilities\Error;
use PHPUtility\System\Utilities\ErrorHandler;

$error = new Error([new ErrorHandler]);
'' + 5;
'' * 5;
$pdo = new \PDO('');
