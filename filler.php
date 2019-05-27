<?php
declare(strict_types=1);

use Classes\BdFiller\BdFiller;

require_once __DIR__ . '/classes/BdFiller.php';

$db = new PDO('sqlite:testtable.sqlite');

$db->query('CREATE TABLE testtable
(
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    h1  varchar(255),
    body text,
    copyright  varchar(255),
    bdate timestamp
)');

$filler = new BdFiller($db, __DIR__ . '/../archive/archive/select.parse3/files');

$filler->fill();
