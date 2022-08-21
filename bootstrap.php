<?php
declare(strict_types=1);

use Jam\PhpProject\Container\DIContainer;
use Jam\PhpProject\Interfaces\ICommentLikesRepository;
use Jam\PhpProject\Interfaces\ICommentsRepository;
use Jam\PhpProject\Interfaces\ILikesRepository;
use Jam\PhpProject\Interfaces\IPostsRepository;
use Jam\PhpProject\Interfaces\IUsersRepository;
use Jam\PhpProject\Repositories\DBCommentLikesRepo;
use Jam\PhpProject\Repositories\DBCommentsRepository;
use Jam\PhpProject\Repositories\DBLikesRepository;
use Jam\PhpProject\Repositories\DBPostsRepository;
use Jam\PhpProject\Repositories\DBUsersRepository;

require_once __DIR__ . '/vendor/autoload.php';
$container = new DIContainer();
$pdo = require_once './sqlite.php';
$container->bind(
    PDO::class,
    $pdo
);
$container->bind(
    IPostsRepository::class,
    DBPostsRepository::class
);
$container->bind(
    IUsersRepository::class,
    DBUsersRepository::class
);
$container->bind(ICommentsRepository::class,DBCommentsRepository::class);
$container->bind(ILikesRepository::class,DBLikesRepository::class);
$container->bind(ICommentLikesRepository::class,DBCommentLikesRepo::class);

return $container;

