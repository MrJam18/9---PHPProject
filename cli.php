<?php
declare(strict_types=1);

use Jam\PhpProject\Common\Comment;
use Jam\PhpProject\Common\Post;
use Jam\PhpProject\Common\User;

require_once 'vendor/autoload.php';

$faker = Faker\Factory::create();
$user = new User(rand(), $faker->firstName(), $faker->lastName());
$post = new Post(rand(), $user->id, $faker->sentence(), $faker->paragraph());
$comment = new Comment(rand(), $user->id, $post->id, $faker->realText());

echo ${$argv[1]};




