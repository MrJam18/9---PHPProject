<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use Exception;
use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Post;
use Jam\PhpProject\Exceptions\InvalidArgumentException;
use Jam\PhpProject\Exceptions\NotFoundException;
use Jam\PhpProject\Interfaces\IPostsRepository;
use PDOException;
use Psr\Log\LoggerInterface;

class DBPostsRepository extends AbstractDBRepo implements IPostsRepository
{
    public function __construct(
        \PDO $connection,
        LoggerInterface $logger
    )
    {
        parent::__construct($connection, 'posts', $logger);
    }

    /**
     * @throws NotFoundException
     * @throws InvalidArgumentException
     */
    function get(UUID $UUID): Post
    {
        try{
            $result = $this->selectOne(['uuid' => $UUID]);
            $authorUUID = new UUID($result['author_uuid']);
            return new Post($UUID, $authorUUID, $result['title'], $result['text']);
        }
        catch (NotFoundException $e) {
            $this->logger->warning('Post was not found: ' . $UUID);
            throw $e;
        }

    }

    function save(Post $post): bool
    {
        $this->insert([
            'uuid' => $post->getUUID(),
            'author_uuid' => $post->getAuthorUUID(),
            'title' => $post->getHeader(),
            'text' => $post->getText()
        ]);
        $this->logger->info("post was created: " . $post->getUUID());
        return true;
    }

    /**
     * @throws NotFoundException
     */
    function delete(UUID $UUID):void
    {
        $statement = $this->connection->prepare('
          DELETE FROM posts WHERE uuid=?');
        $response = $statement->execute([(string)$UUID]);
        if(!$response) throw new PDOException('db query was failed');
        $deletedCount = $statement->rowCount();
        if($deletedCount === 0 ) throw new NotFoundException('row with uuid ' . $UUID . ' was not found');
    }
}