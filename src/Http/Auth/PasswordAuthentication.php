<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Auth;

use Exception;
use Jam\PhpProject\DataBase\User;
use Jam\PhpProject\Exceptions\AuthException;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Interfaces\IPasswordAuthentication;
use Jam\PhpProject\Interfaces\IUsersRepository;

class PasswordAuthentication implements IPasswordAuthentication
{
    public function __construct(
        private readonly IUsersRepository $usersRepository
    ) {
    }


    /**
     * @throws AuthException
     */
    public function user(Request $request): User
    {
        try {
            $username = $request->jsonBodyField('username');
            $user = $this->usersRepository->getByUsername($username);
            $password = $request->jsonBodyField('password');
            $password = hash('sha256', $password);
            if (!$user->checkPassword($password)) {
                throw new AuthException('Wrong password');
            }
        } catch (Exception $e) {
            throw new AuthException($e->getMessage());
        }
        return $user;
    }

}