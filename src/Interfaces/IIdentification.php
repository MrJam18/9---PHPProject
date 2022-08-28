<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;


use Jam\PhpProject\DataBase\User;
use Jam\PhpProject\Http\Request;

interface IIdentification
{
    public function user(Request $request): User;
}