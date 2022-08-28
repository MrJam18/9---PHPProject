<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;


use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Like;

interface ILikesRepository extends IRepository
{
    function save(Like $like):void;
    function get(UUID $UUID):Like;
    function getByPostUUUID(UUID $UUID):array|bool;
    function getByPostAndAuthorUUID(UUID $postUUID, UUID $authorUUID): ?Like;
}