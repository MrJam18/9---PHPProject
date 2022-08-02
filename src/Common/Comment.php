<?php
declare(strict_types=1);

namespace Jam\PhpProject\Common;

class Comment
{
 public function __construct(public int $id, public int $authorId, public int $postId, public string $text)
 {
 }
 public function __toString(): string
 {
     return $this->text;
 }
}