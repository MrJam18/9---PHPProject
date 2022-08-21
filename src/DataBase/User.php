<?php
declare(strict_types=1);

namespace Jam\PhpProject\DataBase;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Interfaces\IDBObject;

class User implements IDBObject
{

    public function __construct(private UUID $UUID, private string $username, private string $name, private string $surname)
    {
    }
    function __toString(): string
    {
        return  "$this->name $this->surname";
    }

    public function getUUID(): UUID
    {
        return $this->UUID;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param UUID $uuid
     */
    public function setUuid(UUID $uuid): void
    {
        $this->UUID = $uuid;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }


}