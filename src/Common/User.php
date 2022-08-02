<?php
declare(strict_types=1);

namespace Jam\PhpProject\Common;

class User
{

    public function __construct(public int $id, public string $name, public string $surname)
    {
    }
    function __toString(): string
    {
        return  "$this->name $this->surname";
    }
}