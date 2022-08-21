<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Like;
use Jam\PhpProject\Exceptions\InvalidArgumentException;
use Jam\PhpProject\Exceptions\NotFoundException;
use Jam\PhpProject\Interfaces\ILikesRepository;

class DBLikesRepository extends AbstractDBRepo implements ILikesRepository
{
    public function __construct(\PDO $connection)
    {
        parent::__construct($connection, 'likes');
    }

    function save(Like $like):void
    {
        $this->insert([
            'UUID' => (string)$like->getUUID(),
            'author_uuid' => (string)$like->getAuthorUUID(),
            'post_uuid' => (string)$like->getPostUUID()
        ]);
    }


    /**
     * @throws NotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $UUID):Like
    {
        $result = $this->selectOne(['uuid' => (string)$UUID]);
        return new Like(
            $UUID,
            new UUID($result['post_uuid']),
            new UUID($result['author_uuid'])
        );
    }
    /**
     * @throws InvalidArgumentException
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
        catch (NotFoundException $exception) {
            return false;
        }

    }
}