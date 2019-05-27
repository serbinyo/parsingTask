<?php
declare(strict_types=1);

use Classes\BdFiller\BdFiller;

require_once __DIR__ . '/classes/BdFiller.php';

$db = new PDO('sqlite:testtable.sqlite');

$filler = new BdFiller($db, __DIR__ . '/../archive/spart');

$filler->fill();
