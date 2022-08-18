<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\User;
use Jam\PhpProject\Exceptions\InvalidArgumentException;
use Jam\PhpProject\Exceptions\UserNotFoundException;
use Jam\PhpProject\Interfaces\IUsersRepository;

class DBUsersRepository implements IUsersRepository
{
    public function __construct(
        private \PDO $connection
    )
    {
    }

    public function save(User $user): bool
    {
// Подготавливаем запрос
        $statement = $this->connection->prepare(
            'INSERT INTO users (first_name, last_name, username, uuid)
VALUES (:first_name, :last_name, :username, :uuid)'
        );
// Выполняем запрос с конкретными значениями
        $statement->execute([
            ':first_name' => $user->getName(),
            ':last_name' => $user->getSurname(),
            ':uuid' => $user->uuid(),
            ':username' => $user->getUsername()
        ]);
        return true;
    }

    /**
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = ?'
        );
        $statement->execute([
            (string)$uuid,
        ]);
        $result = $statement->fetch();
// Бросаем исключение, если пользователь не найден
        if ($result === false) {
            throw new UserNotFoundException(
                "Cannot get user: $uuid"
            );
        }
        return new User(
            new UUID($result['uuid']),
            $result['username'],
            $result['first_name'],
            $result['last_name']
        );
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
// Создаём объект пользователя с полем username
        return new User(
            new UUID($result['uuid']),
            $result['username'],
            $result['first_name'],
            $result['last_name']
        );
    }
}