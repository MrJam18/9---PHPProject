<?php
declare(strict_types=1);

namespace Jam\PhpProject\DataBase;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Interfaces\IDBObject;

class Comment implements IDBObject
{
 public function __construct(private UUID $UUID, private UUID $authorUUID, private UUID $postUUID, private string $text)
 {
 }
 public function __toString(): string
 {
     return $this->text;
 }

    /**
     * @param UUID $authorUUID
     */
    public function setAuthorUUID(UUID $authorUUID): void
    {
        $this->authorUUID = $authorUUID;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param UUID $UUID
     */
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
    public function getUUID(): UUID
    {
        return $this->UUID;
    }

    /**
     * @return UUID
     */
    public function getAuthorUUID(): UUID
    {
        return $this->authorUUID;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return UUID
     */
    public function getPostUUID(): UUID
    {
        return $this->postUUID;
    }
}