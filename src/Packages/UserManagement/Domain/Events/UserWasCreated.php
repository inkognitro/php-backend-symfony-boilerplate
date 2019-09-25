<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\Events;

use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\AuthUserPayload;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\EventTypeId;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceTypeId;
use App\Packages\UserManagement\Application\Query\User\User;
use App\Packages\UserManagement\Application\ResourceAttributes\User\CreatedAt;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\AccessManagement\Application\Query\AuthUser;
use App\Packages\Common\Domain\AuditLog\AuditLogEvent;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\EventId;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\OccurredAt;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\Payload;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerifiedAt;

final class UserWasCreated extends AuditLogEvent
{
    private const ID_KEY = 'id';
    private const USERNAME_KEY = 'username';
    private const EMAIL_ADDRESS_KEY = 'emailAddress';
    private const PASSWORD_HASH_KEY = 'passwordHash';
    private const VERIFIED_AT = 'verifiedAt';
    private const ROLE_ID_KEY = 'roleId';

    public static function occur(User $user, AuthUser $creator): self
    {
        $resourceId = ResourceId::fromString($user->getId()->toString());
        $occurredAt = OccurredAt::create();
        return new self(
            EventId::create(),
            $resourceId,
            self::createPayloadFromUser($user),
            AuthUserPayload::fromAuthUser($creator),
            $occurredAt
        );
    }

    private static function createPayloadFromUser(User $user): Payload
    {
        return Payload::fromArray([
            self::ID_KEY => $user->getId()->toString(),
            self::USERNAME_KEY => $user->getUsername()->toString(),
            self::EMAIL_ADDRESS_KEY => $user->getEmailAddress()->toString(),
            self::PASSWORD_HASH_KEY => $user->getPassword()->toHash(),
            self::ROLE_ID_KEY => $user->getRoleId()->toString(),
            self::VERIFIED_AT => $user->getVerifiedAt()->toNullableString(),
        ]);
    }

    public static function getEventTypeId(): ?EventTypeId
    {
        return EventTypeId::create(UserWasCreated::class);
    }

    public static function findResourceTypeId(): ?ResourceTypeId
    {
        return User::getTypeId();
    }

    public function mustBeLogged(): bool
    {
        return true;
    }

    public function getUser(): User
    {
        $payloadData = $this->getPayload()->toArray();
        return User::create()->modifyByArray([
            UserId::class => UserId::fromString($payloadData[self::ID_KEY]),
            Username::class => Username::fromString($payloadData[self::USERNAME_KEY]),
            EmailAddress::class => EmailAddress::fromString($payloadData[self::EMAIL_ADDRESS_KEY]),
            Password::class => Password::fromHash($payloadData[self::PASSWORD_HASH_KEY]),
            RoleId::class => RoleId::fromString($payloadData[self::ROLE_ID_KEY]),
            CreatedAt::class => CreatedAt::fromDateTime($this->getOccurredAt()->toDateTime()),
            VerifiedAt::class => VerifiedAt::fromNullableString($payloadData[self::VERIFIED_AT]),
        ]);
    }
}