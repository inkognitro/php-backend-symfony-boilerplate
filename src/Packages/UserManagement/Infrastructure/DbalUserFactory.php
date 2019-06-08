<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure;

use App\Packages\UserManagement\Domain\User;
use App\Resources\User\EmailAddress;
use App\Resources\User\UserId;
use App\Resources\User\Username;

final class DbalUserFactory
{
    public function createFromRow(array $row): User
    {
        return new User(
            UserId::fromString($row['id']),
            Username::fromString($row['username']),
            EmailAddress::fromString($row['emailAddress'])
        );
    }
}