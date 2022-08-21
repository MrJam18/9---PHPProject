<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;


use Jam\PhpProject\Common\UUID;

interface IDBObject
{
    function getUUID():UUID;
    public function setUUID(UUID $UUID): void;
    public function __toString(): string;
}