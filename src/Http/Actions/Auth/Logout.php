<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Actions\Auth;

use Jam\PhpProject\Http\AbstractResponse;
use Jam\PhpProject\Http\ErrorResponse;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Http\SuccessfulResponse;
use Jam\PhpProject\Interfaces\IAction;
use Jam\PhpProject\Interfaces\IAuthTokensRepository;
use Jam\PhpProject\Interfaces\ITokenAuthentication;

class Logout implements IAction
{
    public function __construct(
        private readonly IAuthTokensRepository $repository,
        private readonly ITokenAuthentication $authentication
    )
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try{
            $token = $this->authentication->getToken($request);
            $this->repository->doExpired($token);
            return new SuccessfulResponse(['token' => $token]);
        }
        catch (\Exception $e) {
            return new ErrorResponse($e->getMessage());
        }

    }
}