<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Comment;
use Jam\PhpProject\Exceptions\InvalidArgumentException;
use Jam\PhpProject\Exceptions\NotFoundException;
use Jam\PhpProject\Interfaces\ICommentsRepository;
use Psr\Log\LoggerInterface;

class DBCommentsRepository extends AbstractDBRepo implements ICommentsRepository {

    public function __construct(\PDO $connection, LoggerInterface $logger)
    {
        parent::__construct($connection, 'comments', $logger);
    }

    /**
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    function get(UUID $UUID): Comment
    {
        try{
            $result = $this->selectOne(['uuid' => (string)$UUID]);
            $authorUUID = new UUID($result['author_uuid']);
            $postUUID = new UUID($result['post_uuid']);
            return new Comment($UUID, $authorUUID, $postUUID, $result['text']);
        }
        catch (NotFoundException $e){
            $this->logger->warning('comment was not found: ' . $UUID);
            throw $e;
        }

    }

    function save(Comment $comment):void
    {
        $this->insert([
            'uuid' => $comment->getUUID(),
            'author_uuid' => $comment->getAuthorUUID(),
            'post_uuid' => $comment->getPostUUID(),
            'text' => $comment->getText()
        ]);
        $this->logger->info("comment was created: " . $comment->getUUID());
    }

}