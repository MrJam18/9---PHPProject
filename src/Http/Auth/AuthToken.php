<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Auth;

use DateTimeImmutable;
use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Interfaces\IDBObject;

class AuthToken
{
    public function __construct(
        private readonly string $token,
        private readonly UUID $userUuid,
        private readonly DateTimeImmutable $expiresOn
    ) {
    }
    public function token(): string
    {
        return $this->token;
    }

    public function userUuid(): UUID
    {
        return $this->userUuid;
    }

    public function expiresOn(): DateTimeImmutable
    {
        return $this->expiresOn;
    }

    public function __toString(): string
    {
        return $this->token;
    }

}