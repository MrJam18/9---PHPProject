<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Actions;

use Exception;
use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Http\AbstractResponse;
use Jam\PhpProject\Http\ErrorResponse;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Http\SuccessfulResponse;
use Jam\PhpProject\Interfaces\IAction;
use Jam\PhpProject\Interfaces\IPostsRepository;

class DeletePost implements IAction
{
    public function __construct(private readonly IPostsRepository $repository)
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $uuid = $request->query('uuid');
            $uuid = new UUID($uuid);
            $this->repository->delete($uuid);
            return new SuccessfulResponse(['message'=> 'post was deleted']);
        }
        catch (Exception $exception){
            return new ErrorResponse($exception->getMessage());
        }
    }
}