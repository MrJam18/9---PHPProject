<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use Jam\PhpProject\Common\Post;
use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Exceptions\NotFoundException;
use Jam\PhpProject\Interfaces\IPostsRepository;

class DBPostsRepository implements IPostsRepository
{
    public function __construct(
        private \PDO $connection
    )
    {
    }

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

    function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text)
                    VALUES (:uuid, :author_uuid, :title, :text)'
        );
        $statement->execute([
            'uuid' => $post->getUUID(),
            'author_uuid' => $post->getAuthorUUID(),
            'title' => $post->getHeader(),
            'text' => $post->getText()
        ]);
    }
}