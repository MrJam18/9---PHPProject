<?php
declare(strict_types=1);

namespace Jam\PhpProject\Commands;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Comment;
use Jam\PhpProject\DataBase\Post;
use Jam\PhpProject\DataBase\User;
use Jam\PhpProject\Interfaces\ICommentsRepository;
use Jam\PhpProject\Interfaces\IPostsRepository;
use Jam\PhpProject\Interfaces\IUsersRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDB extends Command
{

    public function __construct(
        private readonly \Faker\Generator $faker,
        private readonly IUsersRepository $usersRepository,
        private readonly IPostsRepository $postsRepository,
        private readonly ICommentsRepository $commentsRepository
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addOption('users-number', 'u', InputOption::VALUE_OPTIONAL, 'quantity of created users(integer >= 0)', '10')
            ->addOption('posts-number', 'p', InputOption::VALUE_OPTIONAL, 'quantity of created posts for each user(integer >= 0)', '20')
            ->addOption('comments-number', 'c', InputOption::VALUE_OPTIONAL, 'quantity of created comments for each post(integer >= 0)', '5');
    }
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $users = [];
        $usersAmount = $this->checkIntArgument($input->getOption('users-number'));
        $postsAmount = $this->checkIntArgument($input->getOption('posts-number'));
        $commentsAmount = $this->checkIntArgument($input->getOption('comments-number'));
        if(is_null($usersAmount) || is_null($postsAmount) || is_null($commentsAmount)) {
            $output->writeln('wrong format for option');
            return Command::INVALID;
        }
        for ($i = 0; $i < $usersAmount; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->getUsername());
        }
        $posts = [];
        foreach ($users as $user) {
            for ($i = 0; $i < $postsAmount; $i++) {
                $post = $this->createFakePost($user);
                $posts[] = $post;
                $output->writeln('Post created: ' . $post->getHeader());
            }
        }

        foreach ($posts as $post) {
            for($i = 0; $i < $commentsAmount; $i++) {
                $author = $users[rand(0, $usersAmount - 1)];
                $comment = $this->createFakeComment($post, $author);
                $output->writeln('Comment created: ' . $comment->getUUID());
            }
        }
        return Command::SUCCESS;
    }
    private function createFakeUser(): User
    {
        $gender = rand(0, 1) ? 'male' : 'female';
        $user = User::createFrom(
            $this->faker->userName,
            $this->faker->password,
                $this->faker->firstName($gender),
                $this->faker->lastName($gender)
        );
        $this->usersRepository->save($user);
        return $user;
    }

    private function createFakePost(User $author): Post
    {
        $post = new Post(
            UUID::random(),
            $author->getUUID(),
            $this->faker->sentence(),
            $this->faker->realText
        );
        $this->postsRepository->save($post);
        return $post;
    }

    private function createFakeComment(Post $post, User $author): Comment
    {
        $comment = new Comment(
            UUID::random(),
            $author->getUUID(),
            $post->getUUID(),
            $this->faker->realText
        );
        $this->commentsRepository->save($comment);
        return $comment;
    }

    private function checkIntArgument($argument): ?int
    {
        if($argument === '0') return 0;
        $argument = (int)$argument;
        if($argument <= 0) return null;
        return $argument;
    }
}
