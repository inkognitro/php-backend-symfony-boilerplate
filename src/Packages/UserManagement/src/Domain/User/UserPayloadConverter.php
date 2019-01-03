<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Event;

use App\Packages\Common\Domain\Event\Payload;
use App\Packages\UserManagement\Application\Resources\User\EmailAddress;
use App\Packages\UserManagement\Application\Resources\User\Password;
use App\Packages\UserManagement\Application\Resources\User\Role;
use App\Packages\UserManagement\Application\Resources\User\User;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Application\Resources\User\Username;

final class UserPayloadConverter
{
    public static function convertToPayload(User $user): Payload
    {
        return Payload::fromArray([
            'id' => $user->getId()->toString(),
            'username' => $user->getUsername()->toString(),
            'emailAddress' => $user->getEmailAddress()->toString(),
            'role' => $user->getRole()->toString(),
            'password' => $user->getPassword()->toHash(),
        ]);
    }

    public static function convertToUser(Payload $userPayload): User
    {
        $data = $userPayload->toArray();
        return new User(
            UserId::fromString($data['id']),
            Username::fromString($data['username']),
            EmailAddress::fromString($data['emailAddress']),
            Password::fromHash($data['password']),
            Role::fromString($data['role'])
        );
    }
}