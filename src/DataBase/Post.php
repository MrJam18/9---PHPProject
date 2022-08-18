<?php
declare(strict_types=1);

namespace Jam\PhpProject\DataBase;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Interfaces\IDBObject;

class Post implements IDBObject
{
    public function __construct(private UUID $UUID, private UUID $authorUUID, private string $header, private string $text)
    {
    }
    public function __toString(): string
    {
        return $this->header . PHP_EOL . $this->text . PHP_EOL;
    }

    /**
     * @param UUID $UUID
     */
    public function setUUID(UUID $UUID): void
    {
        $this->UUID = $UUID;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param string $header
     */
    public function setHeader(string $header): void
    {
        $this->header = $header;
    }

    /**
     * @param int $authorUUID
     */
    public function setAuthorUUID(UUID $authorUUID): void
    {
        $this->authorUUID = $authorUUID;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @return int
     */
    public function getAuthorUUID(): UUID
    {
        return $this->authorUUID;
    }

    /**
     * @return UUID
     */
    public function getUUID(): UUID
    {
        return $this->UUID;
    }
}