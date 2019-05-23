<?php

declare(strict_types=1);

define('LOWERCASE', 3);
define('UPPERCASE', 1);

use Classes\Fileselector\Fileselector;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once __DIR__ . '/classes/Fileselector.php';

$log = new Logger('name');
$logger = $log->pushHandler(new StreamHandler('logs/info.log', Logger::INFO));

$fileselector = new Fileselector(__DIR__ . '/../archive/selected');
$fileselector->setUtfCharset();