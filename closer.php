<?php
declare(strict_types=1);

use Classes\TagCloser\TagCloser;

require_once __DIR__ . '/classes/TagCloser.php';

$tagCloser = new TagCloser(__DIR__ . '/../archive/select');

$tagCloser->closeTags();

/*
 * Размер nohup.out после прогона 1.1 Гб
 */
