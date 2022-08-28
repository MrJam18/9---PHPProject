<?php
declare(strict_types=1);


use Jam\PhpProject\Http\Actions\Auth\Login;
use Jam\PhpProject\Http\Actions\Auth\Logout;
use Jam\PhpProject\Http\Actions\CreateComment;
use Jam\PhpProject\Http\Actions\CreateCommentLike;
use Jam\PhpProject\Http\Actions\CreateLike;
use Jam\PhpProject\Http\Actions\CreatePost;
use Jam\PhpProject\Http\Actions\DeletePost;
use Jam\PhpProject\Http\Actions\FindByUserName;
use Jam\PhpProject\Http\Actions\FindByUuid;

$routes = [
    'GET' => [
        '/users/show' => FindByUserName::class,
        '/posts/show' => FindByUuid::class
        ],
    'POST' => [
        '/posts/comment' => CreateComment::class,
        '/posts/createLike' => CreateLike::class,
        '/comments/createLike' => CreateCommentLike::class,
        '/login' => Login::class,
        '/logout' => Logout::class,
        '/posts/create' => CreatePost::class
        ],
    'DELETE' => [
        '/posts' => DeletePost::class
        ]
    ];

return $routes;
