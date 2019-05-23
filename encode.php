<?php
declare(strict_types=1);

use Classes\Fileselector\Fileselector;

require_once __DIR__ . '/classes/Fileselector.php';

$fileselector = new Fileselector(__DIR__ . '/spart');

$fileselector->encode();
