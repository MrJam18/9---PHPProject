<?php
declare(strict_types=1);

namespace Jam\PhpProject\Common;

class Post
{
    public function __construct(public int $id, public int $authorId, public string $header, public string $text)
    {
    }
    public function __toString(): string
    {
        return $this->header . PHP_EOL . $this->text . PHP_EOL;
    }
}