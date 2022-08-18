<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Post;

interface IPostsRepository extends IRepository
{
    function get(UUID $UUID): Post;
    function save(Post $post): bool;
    function delete(UUID $UUID): void;

}