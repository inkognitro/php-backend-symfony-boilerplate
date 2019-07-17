<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\Events;

use App\Resources\Application\AuditLogEvent\Attributes\ResourceType;
use App\Resources\Application\User\Attributes\Password;
use App\Resources\Application\Role\Attributes\RoleId;
use App\Utilities\Authentication\AuthUser as AuthUser;
use App\Packages\Common\Domain\AuditLog\AuditLogEvent;
use App\Resources\Application\AuditLogEvent\Attributes\EventId;
use App\Resources\Application\AuditLogEvent\Attributes\OccurredAt;
use App\Resources\Application\AuditLogEvent\Attributes\Payload;
use App\Resources\Application\AuditLogEvent\Attributes\ResourceId;
use App\Resources\Application\User\Attributes\EmailAddress;
use App\Resources\Application\User\Attributes\User;
use App\Resources\Application\User\Attributes\UserId;
use App\Resources\Application\User\Attributes\Username;

final class UserWasCreated extends AuditLogEvent
{
    public static function occur(
        UserId $userId,
        Username $username,
        EmailAddress $emailAddress,
        Password $password,
        RoleId $roleId,
        AuthUser $creator
    ): self {
        $previousPayload = null;
        $occurredAt = OccurredAt::create();
        $payload = Payload::fromArray([
            Username::getKey() => $username->toString(),
            EmailAddress::getKey() => $emailAddress->toString(),
            Password::getKey() => $password->toHash(),
            RoleId::getKey() => $roleId->toString(),
            Password::getKey() => $password->toHash(),
        ]);
        $resourceId = ResourceId::fromString($userId->toString());
        return new self(EventId::create(), $resourceId, $payload, $creator->toAuditLogEventAuthUserPayload(), $occurredAt);
    }

    public static function getResourceType(): ResourceType
    {
        return ResourceType::fromString(User::class);
    }

    public function getUserId(): UserId
    {
        return UserId::fromString($this->getResourceId()->toString());
    }

    public function getUsername(): Username
    {
        $username = $this->getPayload()->toArray()[Username::getKey()];
        return Username::fromString($username);
    }

    public function getRoleId(): RoleId
    {
        $roleId = $this->getPayload()->toArray()[RoleId::getKey()];
        return RoleId::fromString($roleId);
    }

    public function getEmailAddress(): EmailAddress
    {
        $username = $this->getPayload()->toArray()[EmailAddress::getKey()];
        return EmailAddress::fromString($username);
    }

    public function getPassword(): Password
    {
        $password = $this->getPayload()->toArray()[Password::getKey()];
        return Password::fromString($password);
    }

    public function mustBeLogged(): bool
    {
        return true;
    }
}