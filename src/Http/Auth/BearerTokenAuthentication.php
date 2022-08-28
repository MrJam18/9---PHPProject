<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Auth;

use DateTimeImmutable;
use Exception;
use Jam\PhpProject\DataBase\User;
use Jam\PhpProject\Exceptions\AuthException;
use Jam\PhpProject\Exceptions\HTTPException;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Interfaces\IAuthTokensRepository;
use Jam\PhpProject\Interfaces\ITokenAuthentication;
use Jam\PhpProject\Interfaces\IUsersRepository;
use Psr\Log\LoggerInterface;

class BearerTokenAuthentication implements ITokenAuthentication
{
    private const HEADER_PREFIX = 'Bearer ';

    public function __construct(
       private readonly IAuthTokensRepository $tokensRepository,
        private readonly IUsersRepository $usersRepository,
        private readonly LoggerInterface $logger
    )
    {
    }

    /**
     * @throws AuthException
     */
    public function user(Request $request): User
    {
        try{
            $header = $request->header('Authorization');
            $token = $this->removeBearer($header);
            if (!str_starts_with($header, self::HEADER_PREFIX)) {
                throw new AuthException("Malformed token: [$header]");
            }
            $authToken = $this->tokensRepository->get($token);
            if ($authToken->expiresOn() <= new DateTimeImmutable()) {
                throw new AuthException("Token expired: [$token]");
            }
            $userUuid = $authToken->userUuid();
            return $this->usersRepository->get($userUuid);

        } catch (Exception $e) {
            $this->logger->warning($e->getMessage(), $e->getTrace());
            throw new AuthException($e->getMessage());
        }
    }

    private function removeBearer(string $authHeader): string
    {
        return mb_substr($authHeader, strlen(self::HEADER_PREFIX));
    }

    /**
     * @throws HTTPException
     */
    function getToken(Request $request): string
    {
    return $this->removeBearer($request->header('Authorization'));
    }
}