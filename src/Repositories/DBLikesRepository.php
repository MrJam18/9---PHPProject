<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Like;
use Jam\PhpProject\Exceptions\InvalidArgumentException;
use Jam\PhpProject\Exceptions\NotFoundException;
use Jam\PhpProject\Interfaces\ILikesRepository;
use Psr\Log\LoggerInterface;

class DBLikesRepository extends AbstractDBRepo implements ILikesRepository
{
    public function __construct(\PDO $connection, LoggerInterface $logger)
    {
        parent::__construct($connection, 'likes', $logger);
    }

    function save(Like $like):void
    {
        $this->insert([
            'UUID' => (string)$like->getUUID(),
            'author_uuid' => (string)$like->getAuthorUUID(),
            'post_uuid' => (string)$like->getPostUUID()
        ]);
        $this->logger->info("PostLike was created: " . $like->getUUID());
    }


    /**
     * @throws NotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $UUID):Like
    {
        try{
            $result = $this->selectOne(['uuid' => (string)$UUID]);
            return new Like(
                $UUID,
                new UUID($result['post_uuid']),
                new UUID($result['author_uuid'])
            );
        }
        catch (NotFoundException $e) {
            $this->logger->warning('postLike was not found: ' . $UUID);
            throw $e;
        }

    }

    /**
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    function getByPostUUUID(UUID $UUID): array|bool
    {
        try{
            $where = ['post_uuid' => $UUID];
            $data = $this->select($where);
            return array_map( function ($el){
                $postUUID = new UUID($el['post_uuid']);
                $authorUUID = new UUID($el['author_uuid']);
                $uuid = new UUID($el['uuid']);
                return new Like($uuid, $postUUID, $authorUUID);
            } , $data);
        }
        catch (NotFoundException $e) {
            $this->logger->warning('postLike was not found: ' . $UUID);
            throw $e;
        }
    }

    /**
     * @throws NotFoundException
     */
    function getByPostAndAuthorUUID(UUID $postUUID, UUID $authorUUID): ?Like
    {
        try{
            $where = ['post_uuid' => $postUUID,
                      'author_uuid' => $authorUUID];
            $data = $this->selectOne($where);
            return new Like(
                $data['uuid'],
                $data['post_uuid'],
                $data['author_uuid']
            );
        }
        catch (NotFoundException $e) {
            return null;
        }
    }
}