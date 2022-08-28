<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Actions\Auth;

use DateTimeImmutable;
use Exception;
use Jam\PhpProject\Http\AbstractResponse;
use Jam\PhpProject\Http\Auth\AuthToken;
use Jam\PhpProject\Http\ErrorResponse;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Http\SuccessfulResponse;
use Jam\PhpProject\Interfaces\IAction;
use Jam\PhpProject\Interfaces\IAuthTokensRepository;
use Jam\PhpProject\Interfaces\IPasswordAuthentication;

class Login implements IAction
{
    public function __construct(
        private readonly IPasswordAuthentication $passwordAuthentication,
        private readonly IAuthTokensRepository $authTokensRepository
    ) {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $user = $this->passwordAuthentication->user($request);
            $authToken = new AuthToken(
                bin2hex(random_bytes(40)),
                $user->getUUID(),
                (new DateTimeImmutable())->modify('+1 day')
            );
            $this->authTokensRepository->removeByUserUUID($user->getUUID());
            $this->authTokensRepository->save($authToken);
            return new SuccessfulResponse([
                'token' => (string)$authToken,
            ]);
        } catch (Exception $e) {
            return new ErrorResponse($e->getMessage());
        }

    }
}