<?php
declare(strict_types=1);

namespace Jam\PhpProject\Exceptions;
use Psr\Container\NotFoundExceptionInterface;


class NotFoundException extends \Exception implements NotFoundExceptionInterface{
    
}