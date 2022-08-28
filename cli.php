<?php
declare(strict_types=1);

use Jam\PhpProject\Commands\Arguments;
use Jam\PhpProject\Commands\CreateUserCommand;
use Psr\Log\LoggerInterface;

$container = require_once 'bootstrap.php';

$logger = $container->get(LoggerInterface::class);
$command = $container->get(CreateUserCommand::class);

try {
    $command->handle($argv);
} catch (Exception $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
}




