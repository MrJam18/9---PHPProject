<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Auth;

use Jam\PhpProject\DataBase\User;
use Jam\PhpProject\Exceptions\AuthException;
use Jam\PhpProject\Exceptions\HTTPException;
use Jam\PhpProject\Exceptions\UserNotFoundException;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Interfaces\IAuthentication;
use Jam\PhpProject\Interfaces\IUsersRepository;

class JsonBodyUserNameIdentification implements IAuthentication
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
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }
        try {
            return $this->repository->getByUsername($username);
        } catch (UserNotFoundException $e) {

            throw new AuthException($e->getMessage());
        }
    }
}