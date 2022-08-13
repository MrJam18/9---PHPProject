<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories\tests;

use Jam\PhpProject\Common\User;
use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Exceptions\UserNotFoundException;
use Jam\PhpProject\Interfaces\IUsersRepository;

class DummyUsersRepository implements IUsersRepository {


    public function save(User $user): void
    {
        // TODO: Implement save() method.
    }

    public function get(UUID $UUID): User
    {
        throw new UserNotFoundException("Not found");

    }
    public function getByUsername(string $username): User
    {
// Нас интересует реализация только этого метода
// Для нашего теста не важно, что это будет за пользователь,
// поэтому возвращаем совершенно произвольного
        return new User(UUID::random(), "user123", "first", "last");
    }

}