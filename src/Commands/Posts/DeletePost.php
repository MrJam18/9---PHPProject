<?php
declare(strict_types=1);

namespace Jam\PhpProject\Commands\Posts;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Interfaces\IPostsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeletePost extends Command
{
    public function __construct(private readonly IPostsRepository $repository)
    {
        parent::__construct();
    }
    protected function configure()
    {
        $this
            ->setName('posts:delete')
            ->setDescription('Deletes a post')
            ->addArgument(
                'uuid',
                InputArgument::REQUIRED,
                'UUID of a post to delete'
            );
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $question = new ConfirmationQuestion("Delete post? [y/n]", false);
        if (!$this->getHelper('question')
            ->ask($input, $output, $question)
        ) {
            return Command::SUCCESS;
        }
        $uuid = new UUID($input->getArgument('uuid'));
        $this->repository->delete($uuid);
        $output->writeln("Post $uuid deleted");
        return Command::SUCCESS;
    }

}