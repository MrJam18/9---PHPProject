<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;


use Jam\PhpProject\Http\Request;

interface ITokenAuthentication extends IAuthentication
{
    function getToken(Request $request): string;
}