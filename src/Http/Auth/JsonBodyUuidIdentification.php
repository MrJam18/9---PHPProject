<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Auth;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\User;
use Jam\PhpProject\Exceptions\AuthException;
use Jam\PhpProject\Exceptions\HTTPException;
use Jam\PhpProject\Exceptions\InvalidArgumentException;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Interfaces\IIdentification;
use Jam\PhpProject\Interfaces\IUsersRepository;

class JsonBodyUuidIdentification implements IIdentification
{
    public function __construct(
        private readonly IUsersRepository $repository
    ) {
    }

    /**
     * @throws AuthException
     */
    public function user(Request $request): User
    {
        try {
            $userUuid = new UUID($request->jsonBodyField('user_uuid'));
        } catch (HttpException|InvalidArgumentException $e) {
            throw new AuthException($e->getMessage());
        }
        try {
            return $this->repository->get($userUuid);
        } catch (\Exception $e) {
            throw new AuthException($e->getMessage());
        }
    }
}
