<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;


use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Exceptions\InvalidArgumentException;
use Jam\PhpProject\Exceptions\NotFoundException;

interface IRepository
{
    public function __construct(\PDO $connection);
    /**
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public function get(UUID $UUID);
}