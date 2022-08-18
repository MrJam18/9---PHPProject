<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Comment;
use Jam\PhpProject\Exceptions\InvalidArgumentException;
use Jam\PhpProject\Exceptions\NotFoundException;
use Jam\PhpProject\Interfaces\ICommentsRepository;

class DBCommentsRepository implements ICommentsRepository {

    public function __construct(private \PDO $connection)
    {
    }

    /**
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    function get(UUID $UUID): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = ?'
        );
        $statement->execute([ (string)$UUID ]);
        $result = $statement->fetch();
        if ($result === false) {
            throw new NotFoundException(
                "Cannot get comment: $UUID"
            );
        }
        $authorUUID = new UUID($result['author_uuid']);
        $postUUID = new UUID($result['post_uuid']);
        return new Comment($UUID, $authorUUID, $postUUID, $result['text']);
    }

    function save(Comment $comment): bool
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, author_uuid, post_uuid, text)
                    VALUES (:uuid, :author_uuid, :post_uuid, :text)'
        );
        $statement->execute([
            'uuid' => $comment->getUUID(),
            'author_uuid' => $comment->getAuthorUUID(),
            'post_uuid' => $comment->getPostUUID(),
            'text' => $comment->getText()
        ]);
        return true;
    }

}