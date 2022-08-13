<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories\tests;

use Jam\PhpProject\Common\Post;
use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Exceptions\UserNotFoundException;
use Jam\PhpProject\Interfaces\IPostsRepository;

class MockPostsRepository implements IPostsRepository {

    private bool $saveCalled = false;

    function get(UUID $UUID): Post
    {
       throw new UserNotFoundException();
    }

    function save(Post $post): void
    {
        $this->saveCalled = true;
    }

    function getSaveWasCalled():bool
    {
        return $this->saveCalled;
    }
}