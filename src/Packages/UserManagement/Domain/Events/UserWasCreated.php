<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\Events;

use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\AuthUserPayload;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceTypeId;
use App\Packages\UserManagement\Application\Query\User\User;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;
use App\Packages\Common\Domain\AuditLog\AuditLogEvent;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\EventId;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\OccurredAt;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\Payload;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;

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
            Username::getPayloadKey() => $username->toString(),
            EmailAddress::getPayloadKey() => $emailAddress->toString(),
            Password::getPayloadKey() => $password->toHash(),
            RoleId::getPayloadKey() => $roleId->toString(),
            Password::getPayloadKey() => $password->toHash(),
        ]);
        $resourceId = ResourceId::fromString($userId->toString());
        return new self(EventId::create(), $resourceId, $payload, AuthUserPayload::fromAuthUser($creator), $occurredAt);
    }

    public static function getResourceType(): ResourceTypeId
    {
        return ResourceTypeId::fromString(User::class);
    }

    public function getUserId(): UserId
    {
        return UserId::fromString($this->getResourceId()->toString());
    }

    public function getUsername(): Username
    {
        $username = $this->getPayload()->toArray()[Username::getPayloadKey()];
        return Username::fromString($username);
    }

    public function getRoleId(): RoleId
    {
        $roleId = $this->getPayload()->toArray()[RoleId::getPayloadKey()];
        return RoleId::fromString($roleId);
    }

    public function getEmailAddress(): EmailAddress
    {
        $username = $this->getPayload()->toArray()[EmailAddress::getPayloadKey()];
        return EmailAddress::fromString($username);
    }

    public function getPassword(): Password
    {
        $password = $this->getPayload()->toArray()[Password::getPayloadKey()];
        return Password::fromString($password);
    }

    public function mustBeLogged(): bool
    {
        return true;
    }
}