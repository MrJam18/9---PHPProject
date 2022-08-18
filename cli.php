<?php
declare(strict_types=1);

use Jam\PhpProject\Common\Comment;
use Jam\PhpProject\Common\Post;
use Jam\PhpProject\Common\User;
use Jam\PhpProject\Repositories\DBCommentsRepository;
use Jam\PhpProject\Repositories\DBPostsRepository;
use Jam\PhpProject\Repositories\DBUsersRepository;
use Jam\PhpProject\Common\UUID;

require_once 'vendor/autoload.php';
$connection = require_once 'sqlite.php';
$faker = Faker\Factory::create();


$postRepo = new DBPostsRepository($connection);
$usersRepo = new DBUsersRepository($connection);
$commentsRepo = new DBCommentsRepository($connection);
try{
    $user = new User(UUID::random(), $faker->firstName(), $faker->firstName(), $faker->lastName());
    $usersRepo->save($user);
    $post = new Post(UUID::random(), $user->uuid(), $faker->title(), $faker->text() );
    $postRepo->save($post);
    $comment = new Comment(UUID::random(), $user->uuid(), $post->getUUID(), $faker->realText());
    $commentsRepo->save($comment);
    echo $usersRepo->get($user->uuid());
    echo $postRepo->get($post->getUUID());
    echo $commentsRepo->get($comment->getUUID());
}
catch (Exception $e) {
    echo $e;
}




