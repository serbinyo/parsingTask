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

AnalysisContent::saveContentInFiles(__DIR__ , '/spart/', false);