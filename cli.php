<?php
declare(strict_types=1);

use Jam\PhpProject\Commands\CreateUserCommand;
use Jam\PhpProject\Commands\PopulateDB;
use Jam\PhpProject\Commands\Posts\DeletePost;
use Jam\PhpProject\Commands\Users\CreateUser;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;

$container = require_once 'bootstrap.php';
$app = new Application();

$commandsClasses = [
    CreateUser::class,
    DeletePost::class,
    PopulateDB::class
];
foreach ($commandsClasses as $commandClass) {
    $command = $container->get($commandClass);
    $app->add($command);
}

try {
    $app->run();
} catch (Exception $e) {
    $logger->error($e->getMessage(), $e->getTrace());
}





