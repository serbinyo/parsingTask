<?php
declare(strict_types=1);

$db = new PDO('sqlite:testtable.sqlite');

$db->exec('CREATE TABLE testtable
(
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    h1  varchar(255),
    body text,
    date timestamp
)');

$db->exec("INSERT INTO testtable VALUES (null, 'Первая запись', 'Привет, мир', 1558946369)");


$st = $db->query('SELECT * FROM testtable');

$results = $st->fetchAll();
foreach ($results as $row) {
    echo $row['id'] . PHP_EOL;
}
