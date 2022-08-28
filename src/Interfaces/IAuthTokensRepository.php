<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;


use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Http\Auth\AuthToken;

interface IAuthTokensRepository
{
    public function save(AuthToken $authToken): void;
    public function get(string $token): AuthToken;
    public function doExpired(string $token): void;
    public function removeByUserUUID(UUID $userUUID): void;
}