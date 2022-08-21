<?php

namespace Jam\PhpProject;

$connection = new \PDO('sqlite:' . __DIR__ . '/blog.sqlite', null, null, [
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
]);
$connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
return $connection;