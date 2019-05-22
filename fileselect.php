<?php
declare(strict_types=1);

use Classes\Fileselector\Fileselector;

require_once __DIR__ . '/classes/Fileselector.php';

$fileselector = new Fileselector(__DIR__ . '/../archive/original');
//$fileselector->filesCount();
$fileselector->select(
    [
        '*.asp',
        '*.html',
        '*.xhtml',
        '*.xml',
        '*.txt'
    ]
);

/*
 * Для запуска этого скрипта в фоне набрать
 * nohup php -q fileselect.php 2>&1 &
 *
 * Проверить выполнение
 * ps ax | grep 'fileselect.php'
 */