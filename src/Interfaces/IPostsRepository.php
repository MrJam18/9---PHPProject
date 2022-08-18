<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;

use Jam\PhpProject\Common\Post;
use Jam\PhpProject\Common\UUID;

interface IPostsRepository
{
    function get(UUID $UUID): Post;
    function save(Post $post): void;

}