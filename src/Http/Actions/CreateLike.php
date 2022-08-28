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
use Jam\PhpProject\Interfaces\ITokenAuthentication;

class CreateLike implements IAction
{
    public function __construct(
        private readonly ILikesRepository $repository,
        private readonly ITokenAuthentication $authentication
    )
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $user = $this->authentication->user($request);
            $data = $request->jsonBody();
            $postUUID = new UUID($data['postUUID']);
            $like = $this->repository->getByPostAndAuthorUUID($postUUID, $user->getUUID());
            if($like) {
                 throw new ArgumentsException('This like already exists');
            }
            $like = new Like(UUID::random(), $postUUID, $user->getUUID());
            $this->repository->save($like);
            return new SuccessfulResponse(['uuid'=> (string)$like->getUUID()]);
        }
        catch (\Exception $exception) {
            return new ErrorResponse($exception->getMessage());
        }
    }
}