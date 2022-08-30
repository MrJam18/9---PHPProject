<?php
declare(strict_types=1);

namespace Jam\PhpProject\Commands;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\User;
use Jam\PhpProject\Exceptions\CommandException;
use Jam\PhpProject\Exceptions\UserNotFoundException;
use Jam\PhpProject\Interfaces\IUsersRepository;
use Psr\Log\LoggerInterface;

class CreateUserCommand
{

    public function __construct(
        private readonly IUsersRepository $usersRepository,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function handle(array $rawInput): void
    {
        $this->logger->info("Create user command started");
        $input = $this->parseRawInput($rawInput);
        $username = $input['username'];
        $password = $input['password'];
        if ($this->userExists($username)) {
            $this->logger->warning("user already exists $username");
            return;
        }
        $password = hash('sha256', $password);
        $user = User::createFrom(
            $username,
            $password,
            $input['first_name'],
            $input['last_name']);
        $this->usersRepository->save($user);

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
        foreach (['username', 'first_name', 'last_name', 'password'] as $argument) {
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
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }



}