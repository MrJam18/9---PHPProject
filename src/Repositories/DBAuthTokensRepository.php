<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Exceptions\AuthTokensRepositoryException;
use Jam\PhpProject\Exceptions\InvalidArgumentException;
use Jam\PhpProject\Exceptions\NotFoundException;
use Jam\PhpProject\Http\Auth\AuthToken;
use Jam\PhpProject\Interfaces\IAuthTokensRepository;
use PDO;
use PDOException;
use Psr\Log\LoggerInterface;

class DBAuthTokensRepository extends AbstractDBRepo implements IAuthTokensRepository
{
    public function __construct(PDO $connection, LoggerInterface $logger)
    {
        parent::__construct($connection, 'tokens', $logger);
    }


    /**
     * @throws AuthTokensRepositoryException
     */
    public function save(AuthToken $authToken): void
    {
       $query = <<<'SQL'
        INSERT INTO tokens (
        token,
        user_uuid,
        expires_on
        ) VALUES (
        :token,
        :user_uuid,
        :expires_on
        )
        ON CONFLICT (token) DO UPDATE SET expires_on = :expires_on
        SQL;
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute([
                ':token' => (string)$authToken,
                ':user_uuid' => (string)$authToken->userUuid(),
                ':expires_on' => $authToken->expiresOn()
                    ->format(DateTimeInterface::ATOM),
            ]);
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), (int)$e->getCode(), $e
            );
        }
    }

    /**
     * @throws NotFoundException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function get(string $token): AuthToken
    {
        try{
            $data = $this->selectOne(['token' => $token]);
            return new AuthToken(
                $data['token'],
                new UUID($data['user_uuid']),
                new DateTimeImmutable($data['expires_on'])
            );
        } catch (Exception $e) {
            echo $e;
            throw new AuthTokensRepositoryException(
                $e->getMessage(), $e->getCode(), $e
            );
        }
    }

    /**
     * @throws NotFoundException
     */
    function doExpired(string $token): void
    {
        $updated = [
            'expires_on' => (new DateTimeImmutable())->format(DateTimeInterface::ATOM)
        ];
        $where = [
            'token' => $token
        ];
        $this->update($updated, $where);
    }
    public function removeByUserUUID(UUID $userUUID): void
    {
        $this->destroy(['user_uuid' => $userUUID]);
    }
}