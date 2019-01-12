<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\CreateUser;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\UserManagement\Application\Resources\User\EmailAddress;
use App\Packages\UserManagement\Application\Resources\User\Role;
use App\Packages\UserManagement\Application\Resources\User\User;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Application\Resources\User\Username;
use App\Packages\UserManagement\Application\Resources\User\Password;

final class UserFactory
{
    public function create(CreateUser $command): User
    {
        $role = ($command->getRole() !== null ? $command->getRole() : AuthUser::DEFAULT_USER_ROLE);
        $createdAt = null;
        $updatedAt = null;
        return new User(
            UserId::fromString($command->getUserId()),
            Username::fromString($command->getUsername()),
            EmailAddress::fromString($command->getEmailAddress()),
            Password::fromString($command->getEmailAddress()),
            Role::fromString($role),
            $createdAt,
            $updatedAt
        );
    }
}