<?php

namespace Jam\PhpProject;

$connection = new \PDO('sqlite:' . __DIR__ . '/blog.sqlite');
$connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
return $connection;