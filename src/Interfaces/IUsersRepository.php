<?php
declare(strict_types=1);

namespace Jam\PhpProject\Interfaces;

use Jam\PhpProject\Common\User;
use Jam\PhpProject\Common\UUID;

interface IUsersRepository extends IRepository {
    public function save(User $user): void;
    public function get(UUID $UUID): User;
}
