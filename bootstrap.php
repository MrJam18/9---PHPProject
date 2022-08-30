<?php
declare(strict_types=1);

use Dotenv\Dotenv;
use Faker\Generator;
use Faker\Provider\Lorem;
use Faker\Provider\ru_RU\Internet;
use Faker\Provider\ru_RU\Person;
use Faker\Provider\ru_RU\Text;
use Jam\PhpProject\Container\DIContainer;
use Jam\PhpProject\Http\Auth\BearerTokenAuthentication;
use Jam\PhpProject\Http\Auth\PasswordAuthentication;
use Jam\PhpProject\Interfaces\IAuthentication;
use Jam\PhpProject\Interfaces\IAuthTokensRepository;
use Jam\PhpProject\Interfaces\ICommentLikesRepository;
use Jam\PhpProject\Interfaces\ICommentsRepository;
use Jam\PhpProject\Interfaces\ILikesRepository;
use Jam\PhpProject\Interfaces\IPasswordAuthentication;
use Jam\PhpProject\Interfaces\IPostsRepository;
use Jam\PhpProject\Interfaces\ITokenAuthentication;
use Jam\PhpProject\Interfaces\IUsersRepository;
use Jam\PhpProject\Repositories\DBAuthTokensRepository;
use Jam\PhpProject\Repositories\DBCommentLikesRepo;
use Jam\PhpProject\Repositories\DBCommentsRepository;
use Jam\PhpProject\Repositories\DBLikesRepository;
use Jam\PhpProject\Repositories\DBPostsRepository;
use Jam\PhpProject\Repositories\DBUsersRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/vendor/autoload.php';
Dotenv::createImmutable(__DIR__)->safeLoad();
$container = new DIContainer();
$pdo = require_once './sqlite.php';
$logger = new Logger('blog');
$faker = new Generator();
$faker->addProvider(new Person($faker));
$faker->addProvider(new Text($faker));
$faker->addProvider(new Internet($faker));
$faker->addProvider(new Lorem($faker));


if($_SERVER['LOG_TO_FILES'] === 'yes')
$logger->pushHandler(new StreamHandler(
    __DIR__ . '/logs/blog.log'
));
$logger->pushHandler(new StreamHandler(
    __DIR__ . '/logs/blog.error.log',
    level: Logger::ERROR,
    bubble: false
));
if($_SERVER['LOG_TO_CONSOLE'] === 'yes'){
    $logger->pushHandler(new StreamHandler("php://stdout"));
}

$container->bind(PDO::class, $pdo);
$container->bind(IPostsRepository::class, DBPostsRepository::class);
$container->bind(IUsersRepository::class, DBUsersRepository::class);
$container->bind(ICommentsRepository::class,DBCommentsRepository::class);
$container->bind(ILikesRepository::class,DBLikesRepository::class);
$container->bind(ICommentLikesRepository::class,DBCommentLikesRepo::class);
$container->bind(LoggerInterface::class, $logger);
$container->bind(IAuthentication::class, PasswordAuthentication::class);
$container->bind(IPasswordAuthentication::class, PasswordAuthentication::class);
$container->bind(IAuthTokensRepository::class, DBAuthTokensRepository::class);
$container->bind(IPasswordAuthentication::class, PasswordAuthentication::class);
$container->bind(ITokenAuthentication::class, BearerTokenAuthentication::class);
$container->bind(Generator::class, $faker);





return $container;

