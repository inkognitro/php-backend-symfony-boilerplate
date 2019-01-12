<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\User\Events;

use App\Packages\Common\Application\Resources\CreatedAt;
use App\Packages\Common\Application\Resources\UpdatedAt;
use App\Packages\Common\Application\Resources\Events\AbstractPayload;
use App\Packages\UserManagement\Application\Resources\User\EmailAddress;
use App\Packages\UserManagement\Application\Resources\User\Password;
use App\Packages\UserManagement\Application\Resources\User\Role;
use App\Packages\UserManagement\Application\Resources\User\User;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Application\Resources\User\Username;

final class UserPayload extends AbstractPayload
{
    public static function fromUser(User $user, array $additionalPayloadData = []): self
    {
        $updatedAt = ($user->getUpdatedAt() === null ? null : $user->getUpdatedAt()->toString());
        $createdAt = ($user->getCreatedAt() === null ? null : $user->getCreatedAt()->toString());
        $payloadData = array_merge([
            'id' => $user->getId()->toString(),
            'username' => $user->getUsername()->toString(),
            'emailAddress' => $user->getEmailAddress()->toString(),
            'role' => $user->getRole()->toString(),
            'password' => $user->getPassword()->toHash(),
            'createdAt' => $createdAt,
            'updatedAt' => $updatedAt,
        ], $additionalPayloadData);
        return new self($payloadData);
    }

    public function toUser(): User
    {
        $payloadData = $this->data;
        $createdAt = ($payloadData['createdAt'] === null ? null : CreatedAt::fromString($payloadData['createdAt']));
        $updatedAt = ($payloadData['updatedAt'] === null ? null : UpdatedAt::fromString($payloadData['updatedAt']));
        return new User(
            UserId::fromString($payloadData['id']),
            Username::fromString($payloadData['username']),
            EmailAddress::fromString($payloadData['emailAddress']),
            Password::fromHash($payloadData['password']),
            Role::fromString($payloadData['role']),
            $createdAt,
            $updatedAt
        );
    }
}