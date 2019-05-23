<?php
declare(strict_types=1);

use Classes\Fileselector\Fileselector;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once __DIR__ . '/classes/Fileselector.php';

$log = new Logger('name');
$logger = $log->pushHandler(new StreamHandler('logs/info.log', Logger::INFO));

$fileselector = new Fileselector(__DIR__ . '/../archive/original');

//Подсчет файлов до всех операций
//$count = $fileselector->filesCount();
//$logger->info('Количество файлов:', ['до обработки' => $count]);

//Выборка каталогов с потенциально контентными файлами
//$fileselector->select(
//    [
//        '*.asp',
//        '*.html',
//        '*.xhtml',
//        '*.xml',
//        '*.txt',
//        '*.htm',
//        '*.',
//    ],
//    '/../../archive/select'
//);


//Подсчет выбранных контентных файлов
//$count = $fileselector->filesCount();
//$logger->info('Количество файлов:', [
//    'выбрано файлов' => $count,
//    'критерий отбора' => [
//        '*.asp',
//        '*.html',
//        '*.xhtml',
//        '*.xml',
//        '*.txt',
//        '*.htm',
//        '*.'
//    ]
//]);


//Выборка каталогов содержащих изображения
$fileselector->select(
    ['*.tiff', '*.jpeg', '*.bmp', '*.jpe', '*.jpg', '*.png', '*.gif', '*.psd'],
    '/../../archive/select_images'
);

//Подсчет изображений
//$count = $fileselector->filesCount(['*.tiff', '*.jpeg', '*.bmp', '*.jpe', '*.jpg', '*.png', '*.gif', '*.psd']);
//$logger->info('Количество файлов:', [
//    'выбрано файлов' => $count,
//    'критерий отбора' => ['*.tiff', '*.jpeg', '*.bmp', '*.jpe', '*.jpg', '*.png', '*.gif', '*.psd'],
//]);


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
 * [2019-05-23 14:12:48] name.INFO: Количество файлов: {"выбрано файлов":21989,"критерий отбора":["*.htm"]} []
 */
