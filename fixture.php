<?php
declare(strict_types=1);


$connection = require_once './sqlite.php';

//require_once 'vendor/autoload.php';
$connection->exec('CREATE TABLE users (
    uuid text primary key,
    username text,
    first_name text,
    last_name text
)');

$connection->exec('CREATE TABLE posts (
    uuid text PRIMARY KEY,
    author_uuid TEXT,
    title TEXT,
    text TEXT,
    FOREIGN KEY (author_uuid) REFERENCES users(uuid)
                   )');

$connection->exec('CREATE TABLE comments (
    uuid TEXT PRIMARY KEY,
    author_uuid TEXT,
    post_uuid TEXT,
    text TEXT,
    FOREIGN KEY (author_uuid) REFERENCES users(uuid),
    FOREIGN KEY (post_uuid) REFERENCES posts(uuid)
)');