<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Actions;

use Jam\PhpProject\Http\AbstractResponse;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Interfaces\IAction;
use Jam\PhpProject\Interfaces\IPostsRepository;

class SavePost implements IAction
{
    public function __construct( private readonly IPostsRepository $repository)
    {
    }

    public function handle(Request $request): AbstractResponse
    {

    }
}