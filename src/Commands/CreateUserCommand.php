<?php
declare(strict_types=1);

namespace Jam\PhpProject\Commands;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\User;
use Jam\PhpProject\Exceptions\CommandException;
use Jam\PhpProject\Exceptions\UserNotFoundException;
use Jam\PhpProject\Interfaces\IUsersRepository;

class CreateUserCommand
{
// Команда зависит от контракта репозитория пользователей,
// а не от конкретной реализации
    public function __construct(
        private IUsersRepository $usersRepository
    )
    {
    }

    public function handle(array $rawInput): void
    {
        $input = $this->parseRawInput($rawInput);
        $username = $input['username'];
// Проверяем, существует ли пользователь в репозитории
        if ($this->userExists($username)) {
// Бросаем исключение, если пользователь уже существует
            throw new CommandException("User already exists: $username");
        }
        $this->usersRepository->save(new User(
            UUID::random(),
            $username,
            $input['first_name'],
            $input['last_name']
        ));

    }
    private function parseRawInput(array $rawInput): array
    {
        $input = [];
        foreach ($rawInput as $argument) {
            $parts = explode('=', $argument);
            if (count($parts) !== 2) {
                continue;
            }
            $input[$parts[0]] = $parts[1];
        }
        foreach (['username', 'first_name', 'last_name'] as $argument) {
            if (!array_key_exists($argument, $input)) {
                throw new CommandException(
                    "No required argument provided: $argument"
                );
            }
            if (empty($input[$argument])) {
                throw new CommandException("Empty argument provided: $argument");
            }
        }
        return $input;
    }

    private function userExists(string $username): bool
    {
        try {
// Пытаемся получить пользователя из репозитория
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }



}