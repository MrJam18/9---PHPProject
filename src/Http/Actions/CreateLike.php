<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Actions;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Like;
use Jam\PhpProject\Exceptions\ArgumentsException;
use Jam\PhpProject\Http\AbstractResponse;
use Jam\PhpProject\Http\ErrorResponse;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Http\SuccessfulResponse;
use Jam\PhpProject\Interfaces\IAction;
use Jam\PhpProject\Interfaces\ILikesRepository;

class CreateLike implements IAction
{
    public function __construct(
        private readonly ILikesRepository $repository
    )
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $data = $request->jsonBody();
            $postUUID = new UUID($data['postUUID']);
            $authorUUID = new UUID($data['authorUUID']);
            $likes = $this->repository->getByPostUUUID($postUUID);
            if($likes !== false) {
                foreach ($likes as $like) {
                    if($like->getAuthorUUID() == $data['authorUUID']) throw new ArgumentsException('This like already exists');
                }
            }
            $like = new Like(UUID::random(), $postUUID, $authorUUID);
            $this->repository->save($like);
            return new SuccessfulResponse(['uuid'=> (string)$like->getUUID()]);
        }
        catch (\Exception $exception) {
            return new ErrorResponse($exception->getMessage());
        }
    }
}