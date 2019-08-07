<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure;

use App\Packages\UserManagement\Domain\UserParamsValidation\User;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;

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