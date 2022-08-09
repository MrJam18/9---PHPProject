<?php
declare(strict_types=1);

namespace Jam\PhpProject\Common;

class Comment
{
 public function __construct(private UUID $UUID, private UUId $authorUUID, private UUId $postUUID, private string $text)
 {
 }
 public function __toString(): string
 {
     return $this->text;
 }

    /**
     * @param UUId $authorUUID
     */
    public function setAuthorUUID(UUId $authorUUID): void
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
     * @param UUId $postUUID
     */
    public function setPostUUID(UUId $postUUID): void
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
     * @return UUId
     */
    public function getAuthorUUID(): UUId
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
     * @return UUId
     */
    public function getPostUUID(): UUId
    {
        return $this->postUUID;
    }
}