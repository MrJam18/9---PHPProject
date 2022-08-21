<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;


use Jam\PhpProject\Http\AbstractResponse;
use Jam\PhpProject\Http\Request;

interface IAction
{
    public function handle(Request $request): AbstractResponse;
}