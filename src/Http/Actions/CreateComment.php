<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Actions;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Comment;
use Jam\PhpProject\Exceptions\InvalidArgumentException;
use Jam\PhpProject\Http\AbstractResponse;
use Jam\PhpProject\Http\ErrorResponse;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Http\SuccessfulResponse;
use Jam\PhpProject\Interfaces\IAction;
use Jam\PhpProject\Interfaces\ICommentsRepository;
use Jam\PhpProject\Interfaces\IPostsRepository;
use Jam\PhpProject\Interfaces\IUsersRepository;

class CreateComment implements IAction
{
    public function __construct(
        private readonly ICommentsRepository $repository,
        private readonly IUsersRepository $usersRepository,
        private readonly IPostsRepository $postsRepository
    )
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $data = $request->jsonBody();
            $expectedKeys = [
                'author_uuid',
                'post_uuid',
                'text'
            ];
            $dataKeys = array_keys($data);
            $compared = array_diff($expectedKeys, $dataKeys);
            if(count($compared) !== 0) throw new InvalidArgumentException('dont have all necessary keys in request body');
            $author_uuid = new UUID($data['author_uuid']);
            $post_uuid = new UUID($data['post_uuid']);
            $this->usersRepository->get($author_uuid);
            $this->postsRepository->get($post_uuid);
            $comment = new Comment(UUID::random(), $author_uuid, $post_uuid, $data['text']);
            $this->repository->save($comment);
            return new SuccessfulResponse(['message' => "comment was saved"]);
        }
        catch (\Exception $exception) {
            return new ErrorResponse($exception->getMessage());
        }



    }
}