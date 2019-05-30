<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: perale
 * Date: 24.05.19
 * Time: 9:40
 */
use Classes\AnalysisContent;

require_once __DIR__ . '/classes/AnalysisContent.php';

//AnalysisContent::deleteUnnecessaryTags(__DIR__ , '/archive/select/retail.ru/1999*');
//AnalysisContent::deleteUnnecessaryTags(__DIR__ , '/spart/');
//AnalysisContent::getContentFirst(__DIR__, '/archive/select/retail.ru/');
AnalysisContent::getContentTwo(__DIR__, '/archive_table');
//echo 'after analysis: ' . date('h:i:s A');
