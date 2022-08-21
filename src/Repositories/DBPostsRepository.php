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

class DBPostsRepository implements IPostsRepository
{
    public function __construct(
        private \PDO $connection
    )
    {
    }

    /**
     * @throws NotFoundException
     * @throws InvalidArgumentException
     */
    function get(UUID $UUID): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = ?'
        );
        $statement->execute([ (string)$UUID ]);
        $result = $statement->fetch();
        if ($result === false) {
            throw new NotFoundException(
                "Cannot get post: $UUID"
            );
        }
        $authorUUID = new UUID($result['author_uuid']);
        return new Post($UUID, $authorUUID, $result['title'], $result['text']);
    }

    function save(Post $post): bool
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text)
                    VALUES (:uuid, :author_uuid, :title, :text)'
        );
        return $statement->execute([
            'uuid' => $post->getUUID(),
            'author_uuid' => $post->getAuthorUUID(),
            'title' => $post->getHeader(),
            'text' => $post->getText()
        ]);

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