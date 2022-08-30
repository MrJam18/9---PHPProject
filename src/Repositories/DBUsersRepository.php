<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\User;
use Jam\PhpProject\Exceptions\InvalidArgumentException;
use Jam\PhpProject\Exceptions\NotFoundException;
use Jam\PhpProject\Exceptions\UserNotFoundException;
use Jam\PhpProject\Interfaces\IUsersRepository;
use Psr\Log\LoggerInterface;

class DBUsersRepository extends AbstractDBRepo implements IUsersRepository
{
    public function __construct(
        \PDO $connection,
        LoggerInterface $logger
    )
    {
        parent::__construct($connection, 'users', $logger);
    }

    public function save(User $user): bool
    {

        $this->insert([
            'first_name' => $user->getName(),
            'last_name' => $user->getSurname(),
            'password' => $user->getHashedPassword(),
            'uuid' => $user->getUUID(),
            'username' => $user->getUsername()
        ]);
        $this->logger->info('user was saved: ' . $user->getUUID());
        return true;
    }

    /**
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     * @throws NotFoundException
     */
    public function get(UUID $UUID): User
    {
        try{
            $result = $this->selectOne(['uuid' => $UUID]);
            return new User(
                new UUID($result['uuid']),
                $result['username'],
                $result['password'],
                $result['first_name'],
                $result['last_name']
            );
        }
        catch (NotFoundException $e){
            $this->logger->warning('User was not found: ' . $UUID);
            throw $e;
        }

    }

    /**
     * @throws UserNotFoundException
     */
    function getByUserName(string $username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = ?'
        );
        $statement->execute([$username]);
        return $this->getUser($statement, $username);
    }

    private function getUser(\PDOStatement $statement, string $username): User
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new UserNotFoundException(
                "Cannot find user: $username"
            );
        }
        return new User(
            new UUID($result['uuid']),
            $result['username'],
            $result['password'],
            $result['first_name'],
            $result['last_name']
        );
    }
}