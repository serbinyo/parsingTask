<?php
declare(strict_types=1);

use Classes\Encoder\Encoder;

require_once __DIR__ . '/classes/Encoder.php';

$fileselector = new Encoder(__DIR__ . '/../archive/spart');

$fileselector->encode();
