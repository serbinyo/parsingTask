<?php
declare(strict_types=1);

use Classes\Fileselector\Fileselector;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once __DIR__ . '/classes/Fileselector.php';

$log = new Logger('name');
$logger = $log->pushHandler(new StreamHandler('logs/info.log', Logger::INFO));

$fileselector = new Fileselector(__DIR__ . '/../archive/select.image');

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
//$fileselector->select(
//    ['*.tiff', '*.jpeg', '*.bmp', '*.jpe', '*.jpg', '*.png', '*.gif', '*.psd'],
//    '/../../archive/select_images'
//);

//Подсчет изображений
//$count = $fileselector->filesCount(['*.tiff', '*.jpeg', '*.bmp', '*.jpe', '*.jpg', '*.png', '*.gif', '*.psd']);
//$logger->info('Количество файлов:', [
//    'выбрано файлов' => $count,
//    'критерий отбора' => ['*.tiff', '*.jpeg', '*.bmp', '*.jpe', '*.jpg', '*.png', '*.gif', '*.psd'],
//]);


//Подсчет форматов
$count = $fileselector->filesCount(['*.asp', '*.html', '*.xhtml', '*.xml', '*.txt', '*.htm', '*.']);
$logger->info('Количество файлов:', [
    'выбрано файлов' => $count,
    'критерий отбора' => ['*.asp', '*.html', '*.xhtml', '*.xml', '*.txt', '*.htm', '*.'],
]);

//$count = $fileselector->filesCount(['*.html']);
//$logger->info('Количество файлов:', [
//    'выбрано файлов' => $count,
//    'критерий отбора' => ['*.html'],
//]);

//$count = $fileselector->filesCount(['*.xhtml']);
//$logger->info('Количество файлов:', [
//    'выбрано файлов' => $count,
//    'критерий отбора' => ['*.xhtml'],
//]);

//$count = $fileselector->filesCount(['*.xml']);
//$logger->info('Количество файлов:', [
//    'выбрано файлов' => $count,
//    'критерий отбора' => ['*.xml'],
//]);

//$count = $fileselector->filesCount(['*.txt']);
//$logger->info('Количество файлов:', [
//    'выбрано файлов' => $count,
//    'критерий отбора' => ['*.txt'],
//]);

//$count = $fileselector->filesCount(['*.']);
//$logger->info('Количество файлов:', [
//    'выбрано файлов' => $count,
//    'критерий отбора' => ['*.'],
//]);

//$count = $fileselector->filesCount(['*.htm']);
//$logger->info('Количество файлов:', [
//    'выбрано файлов' => $count,
//    'критерий отбора' => ['*.htm'],
//]);

//$count = $fileselector->filesCount(['*.asp']);
//$logger->info('Количество файлов:', [
//    'выбрано файлов' => $count,
//    'критерий отбора' => ['*.asp'],
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
 * [2019-05-24 07:38:38] name.INFO: Количество файлов: {"выбрано файлов":5899,"критерий отбора":['*.tiff', '*.jpeg', '*.bmp', '*.jpe', '*.jpg', '*.png', '*.gif', '*.psd']} []
 * [2019-05-26 10:01:10] name.INFO: Количество файлов: {"выбрано файлов":126490,"критерий отбора":["*.asp","*.html","*.xhtml","*.xml","*.txt","*.htm","*."]} []
 * [2019-05-26 10:04:01] name.INFO: Количество файлов: {"выбрано файлов":69345,"критерий отбора":["*.html"]} []
 * [2019-05-26 10:04:57] name.INFO: Количество файлов: {"выбрано файлов":0,"критерий отбора":["*.xhtml"]} []
 * [2019-05-26 10:05:55] name.INFO: Количество файлов: {"выбрано файлов":10939,"критерий отбора":["*.xml"]} []
 * [2019-05-26 10:07:05] name.INFO: Количество файлов: {"выбрано файлов":112,"критерий отбора":["*.txt"]} []
 * [2019-05-26 10:07:56] name.INFO: Количество файлов: {"выбрано файлов":79,"критерий отбора":["*."]} []
 * [2019-05-26 10:09:45] name.INFO: Количество файлов: {"выбрано файлов":21989,"критерий отбора":["*.htm"]} []
 * [2019-05-26 10:11:28] name.INFO: Количество файлов: {"выбрано файлов":24026,"критерий отбора":["*.asp"]} []
 */
