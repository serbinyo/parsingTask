<?php
declare(strict_types=1);

use Classes\BdFiller\BdFiller;

require_once __DIR__ . '/classes/BdFiller.php';

$db = new PDO('sqlite:testtable1.sqlite');

$db->query('CREATE TABLE testtable1
(
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    h1  varchar(255),
    body text,
    copyright  varchar(255),
    bdate timestamp
)');

$filler = new BdFiller($db, __DIR__ . '/spart');

$filler->fill();
