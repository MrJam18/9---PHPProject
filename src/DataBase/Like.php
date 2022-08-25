<?php
declare(strict_types=1);

namespace Jam\PhpProject\DataBase;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Interfaces\IDBObject;

class Like implements IDBObject
{
    public function __construct(
        private UUID $UUID,
        private UUID $postUUID,
        private UUID $authorUUID
    )
    {
    }

    public function __toString(): string
    {
        return "user with UUID $this->authorUUID has liked";
    }

    function getUUID(): UUID
    {
        return $this->UUID;
    }

    public function setUUID(UUID $UUID): void
    {
        $this->UUID = $UUID;
    }

    /**
     * @param UUID $postUUID
     */
    public function setPostUUID(UUID $postUUID): void
    {
        $this->postUUID = $postUUID;
    }

    /**
     * @return UUID
     */
    public function getPostUUID(): UUID
    {
        return $this->postUUID;
    }

    /**
     * @param UUID $authorUUID
     */
    public function setAuthorUUID(UUID $authorUUID): void
    {
        $this->authorUUID = $authorUUID;
    }

    /**
     * @return UUID
     */
    public function getAuthorUUID(): UUID
    {
        return $this->authorUUID;
    }
}