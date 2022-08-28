<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\CommentLike;
use Jam\PhpProject\Exceptions\InvalidArgumentException;
use Jam\PhpProject\Exceptions\NotFoundException;
use Jam\PhpProject\Interfaces\ICommentLikesRepository;
use Psr\Log\LoggerInterface;

class DBCommentLikesRepo extends AbstractDBRepo implements ICommentLikesRepository
{
    public function __construct(\PDO $connection, LoggerInterface $logger)
    {
        parent::__construct($connection, 'comment_likes', $logger);
    }

    public function get(UUID $UUID): CommentLike
    {
        try{
            $data = $this->selectOne(['uuid' => $UUID]);
            return new CommentLike(
                new UUID($data['uuid']),
                new UUID($data['comment_uuid']),
                new UUID($data['author_uuid'])
            );
        }
        catch (NotFoundException $e) {
            $this->logger->warning("commentLike not found: $UUID");
            throw $e;
        }

    }

    function save(CommentLike $like): void
    {

        $this->insert([
            'uuid'=> $like->getUUID(),
            'author_uuid' => $like->getAuthorUUID(),
            'comment_uuid' => $like->getCommentUUID()
        ]);
        $this->logger->info("commentLike was created: " . $like->getUUID());
    }

    /**
     * @throws InvalidArgumentException|NotFoundException
     */
    public function getByCommentUUID(UUID $UUID): array|bool
    {
        try {
            $data = $this->select(['comment_uuid' => $UUID]);
            return array_map( function ($el){
                return new CommentLike(
                    new UUID($el['uuid']),
                    new UUID($el['comment_uuid']),
                    new UUID($el['author_uuid'])
                );
            }, $data);
        }
        catch (NotFoundException) {
            return false;
        }

    }


}