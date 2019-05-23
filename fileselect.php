<?php
declare(strict_types=1);

use Classes\Fileselector\Fileselector;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once __DIR__ . '/classes/Fileselector.php';

$log = new Logger('name');
$logger = $log->pushHandler(new StreamHandler('logs/info.log', Logger::INFO));

$fileselector = new Fileselector(__DIR__ . '/../archive/original');

//$count = $fileselector->filesCount();
//$logger->info('Количество файлов:', ['до обработки' => $count]);

$fileselector->select(
    [
        '*.asp',
        '*.html',
        '*.xhtml',
        '*.xml',
        '*.txt',
        '*.htm'
    ],
    '/../../archive/selected'
);

$count = $fileselector->filesCount();
$logger->info('Количество файлов:', [
    'выбрано файлов' => $count,
    'критерий отбора' => [
        '*.asp',
        '*.html',
        '*.xhtml',
        '*.xml',
        '*.txt',
        '*.htm'
    ]
]);


/*
 * Для запуска этого скрипта в фоне набрать
 * nohup php -q fileselect.php 2>&1 &
 *
 * Проверить выполнение
 * ps ax | grep 'fileselect.php'
 *
 * Результат:
 * [2019-05-22 17:58:42] name.INFO: Количество файлов: {"до обработки":152517} []
 * [2019-05-23 01:36:07] name.INFO: Количество файлов: {"выбрано файлов":104423,"критерий отбора":["*.asp","*.html","*.xhtml","*.xml","*.txt"]} []
 */

//TODO проверить файлы без разширения *.
