<?php
declare(strict_types=1);

namespace Jam\PhpProject\Common;

use Jam\PhpProject\Exceptions\InvalidArgumentException;

class UUID
{
// Внутри объекта мы храним UUID как строку
    public function __construct(
        private string $uuidString
    ) {
// Если входная строка не подходит по формату -
// бросаем исключение InvalidArgumentException
// (его мы тоже добавили)
//
// Таким образом, мы гарантируем, что если объект
// был создан, то он точно содержит правильный UUID
        if (!uuid_is_valid($uuidString)) {
            throw new InvalidArgumentException(
                "Malformed UUID: $this->uuidString"
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function random(): self
    {
        return new self(uuid_create(UUID_TYPE_RANDOM));
    }
    public function __toString(): string
    {
        return $this->uuidString;
    }

    /**
     * @param string $uuidString
     */
    public function setUuidString(string $uuidString): void
    {
        $this->uuidString = $uuidString;
    }

    /**
     * @return string
     */
    public function getUuidString(): string
    {
        return $this->uuidString;
    }

}