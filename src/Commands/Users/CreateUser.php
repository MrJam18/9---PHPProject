<?php
declare(strict_types=1);

namespace Jam\PhpProject\Commands\Users;

use Jam\PhpProject\DataBase\User;
use Jam\PhpProject\Exceptions\UserNotFoundException;
use Jam\PhpProject\Interfaces\IUsersRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    public function __construct(private readonly IUsersRepository $repository)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('users:create')
            ->setDescription('Creates new user')
            ->addArgument('first_name', InputArgument::REQUIRED, 'First name')
            ->addArgument('last_name', InputArgument::REQUIRED, 'Last name')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create user command started');
        $username = $input->getArgument('username');
        if ($this->userExists($username)) {
            $output->writeln("User already exists: $username");
            return Command::FAILURE;
        }
        $user = User::createFrom(
            $username,
            $input->getArgument('password'),
            $input->getArgument('first_name'),
            $input->getArgument('last_name')
            );
        $this->repository->save($user);
        $output->writeln('User created: ' . $user->getUUID());
        return Command::SUCCESS;
    }


    private function userExists(string $username): bool
    {
        try {
            $this->repository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }

}