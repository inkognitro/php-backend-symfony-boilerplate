<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Events;

use App\Resources\User\Password;
use App\Resources\UserRole\RoleId;
use App\Utilities\AuthUser as AuthUser;
use App\Packages\Common\Domain\Event\AuditLogEvent;
use App\Resources\AuditLogEvent\EventId;
use App\Resources\AuditLogEvent\OccurredAt;
use App\Resources\AuditLogEvent\Payload;
use App\Resources\AuditLogEvent\ResourceId;
use App\Resources\User\EmailAddress;
use App\Resources\User\User;
use App\Resources\User\UserId;
use App\Resources\User\Username;

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

    public static function getResourceType(): string
    {
        return User::class;
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