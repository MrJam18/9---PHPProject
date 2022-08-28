<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Actions;

use Exception;
use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Post;
use Jam\PhpProject\Http\AbstractResponse;
use Jam\PhpProject\Http\ErrorResponse;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Http\SuccessfulResponse;
use Jam\PhpProject\Interfaces\IAction;
use Jam\PhpProject\Interfaces\IPostsRepository;
use Jam\PhpProject\Interfaces\ITokenAuthentication;

class CreatePost implements IAction
{
    public function __construct(
        private readonly IPostsRepository $repository,
        private readonly ITokenAuthentication $authentication,
    )
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try{
            $user = $this->authentication->user($request);
            $body = $request->jsonBody();
            $post = new Post(
                UUID::random(),
                $user->getUUID(),
                $body['title'],
                $body['text']
            );
            $this->repository->save($post);
            return new SuccessfulResponse(['uuid' => (string)$post->getUUID()]);
        } catch (Exception $e) {
            return new ErrorResponse($e->getMessage());
        }


    }
}