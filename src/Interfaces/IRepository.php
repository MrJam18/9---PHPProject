<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;


use Jam\PhpProject\Common\UUID;

interface IRepository
{
    public function __construct(\PDO $connection);
//    function save($dsa):void;
    function get(UUID $UUID):mixed;
}