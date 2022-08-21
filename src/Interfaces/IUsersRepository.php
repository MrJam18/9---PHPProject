<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\User;

interface IUsersRepository extends IRepository {
    public function save(User $user): bool;
    public function get(UUID $UUID): User;
}
