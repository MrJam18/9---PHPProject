<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories\tests;

use Jam\PhpProject\Common\Comment;
use Jam\PhpProject\Common\Post;
use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Exceptions\UserNotFoundException;
use Jam\PhpProject\Interfaces\ICommentsRepository;

class MockCommentsRepo implements ICommentsRepository
{
    private bool $saveCalled = false;

    function get(UUID $UUID): Comment
    {
        throw new UserNotFoundException();
    }

    function save(Comment $comment): void
    {
        $this->saveCalled = true;
    }

    function getSaveWasCalled():bool
    {
        return $this->saveCalled;
    }

}