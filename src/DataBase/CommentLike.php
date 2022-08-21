<?php
declare(strict_types=1);

namespace Jam\PhpProject\DataBase;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Interfaces\IDBObject;

class CommentLike implements IDBObject
{
    public function __construct(private UUID $UUID,
                                private UUID $commentUUID,
                                private UUID $authorUUID)
    {
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
     * @return UUID
     */
    public function getAuthorUUID(): UUID
    {
        return $this->authorUUID;
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
    public function getCommentUUID(): UUID
    {
        return $this->commentUUID;
    }

    /**
     * @param UUID $commentUUID
     */
    public function setCommentUUID(UUID $commentUUID): void
    {
        $this->commentUUID = $commentUUID;
    }

    public function __toString(): string
    {
       return (string) $this->UUID;
    }
}