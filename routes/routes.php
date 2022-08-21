<?php
declare(strict_types=1);


use Jam\PhpProject\Http\Actions\CreateComment;
use Jam\PhpProject\Http\Actions\DeletePost;
use Jam\PhpProject\Http\Actions\FindByUserName;
use Jam\PhpProject\Http\Actions\FindByUuid;
use Jam\PhpProject\Repositories\DBCommentsRepository;
use Jam\PhpProject\Repositories\DBPostsRepository;
use Jam\PhpProject\Repositories\DBUsersRepository;

$pdo = require_once 'sqlite.php';
$routes = [
    'GET' => [
        '/users/show' => new FindByUsername(
            new DBUsersRepository(
                $pdo
            )
        ),
        '/posts/show' => new FindByUuid(
            new DBPostsRepository(
                $pdo
            )
        ),
    ],
    'POST' => [
        '/posts/comment' => new CreateComment(
            new DBCommentsRepository($pdo), new DBUsersRepository($pdo), new DBPostsRepository($pdo)
        )
    ],
    'DELETE' => [
        '/posts' => new DeletePost(
            new DBPostsRepository($pdo)
        )
    ]
];


return $routes;
