<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;

use Jam\PhpProject\Common\Comment;
use Jam\PhpProject\Common\UUID;

interface ICommentsRepository
{
    function get(UUID $UUID): Comment;
    function save(Comment $comment): void;
}