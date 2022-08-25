<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Actions;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\CommentLike;
use Jam\PhpProject\Exceptions\ArgumentsException;
use Jam\PhpProject\Http\AbstractResponse;
use Jam\PhpProject\Http\ErrorResponse;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Http\SuccessfulResponse;
use Jam\PhpProject\Interfaces\IAction;
use Jam\PhpProject\Interfaces\ICommentLikesRepository;

class CreateCommentLike implements IAction
{
    public function __construct(
        private readonly ICommentLikesRepository $repository
    )
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $data = $request->jsonBody();
            $commentUUID = new UUID($data['comment_uuid']);
            $authorUUID = new UUID($data['author_uuid']);
            $likes = $this->repository->getByCommentUUID($commentUUID);
            if($likes !== false) {
                foreach ($likes as $like) {
                    if($like->getAuthorUUID() == $data['author_uuid']) throw new ArgumentsException('This like already exists');
                }
            }
            $like = new CommentLike(UUID::random(), $commentUUID, $authorUUID);
            $this->repository->save($like);
            return new SuccessfulResponse(['uuid'=> (string)$like->getUUID()]);
        }
        catch (\Exception $exception) {
            return new ErrorResponse($exception->getMessage());
        }
    }
}