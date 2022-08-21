<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;


use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\CommentLike;

interface ICommentLikesRepository extends IRepository
{
    public function get(UUID $UUID):CommentLike;

    function save(CommentLike $like):void;
    function getByCommentUUID(UUID $UUID):array|bool;

}