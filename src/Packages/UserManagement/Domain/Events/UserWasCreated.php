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
    private const USERNAME_KEY = 'username';
    private const EMAIL_ADDRESS_KEY = 'emailAddress';
    private const PASSWORD_HASH_KEY = 'passwordHash';
    private const VERIFIED_AT = 'verifiedAt';
    private const ROLE_ID_KEY = 'roleId';

    public static function occur(
        UserId $userId,
        Username $username,
        EmailAddress $emailAddress,
        Password $password,
        RoleId $roleId,
        VerifiedAt $verifiedAt,
        AuthUser $creator
    ): self {
        $resourceId = ResourceId::fromString($userId->toString());
        $occurredAt = OccurredAt::create();
        $userChange = Payload\ResourceChange::create(ResourceTypeId::user(), $resourceId);
        $userChange = $userChange->addAttributeChangeFromScalars(self::USERNAME_KEY, $username->toString());
        $userChange = $userChange->addAttributeChangeFromScalars(self::EMAIL_ADDRESS_KEY, $emailAddress->toString());
        $userChange = $userChange->addAttributeChangeFromScalars(self::PASSWORD_HASH_KEY, $password->toHash());
        $userChange = $userChange->addAttributeChangeFromScalars(self::ROLE_ID_KEY, $roleId->toString());
        if($verifiedAt->toNullableString() !== null) {
            $userChange = $userChange->addAttributeChangeFromScalars(self::VERIFIED_AT, $verifiedAt->toNullableString());
        }
        $payload = Payload::create()->addResourceChange($userChange);
        return new self(EventId::create(), $resourceId, $payload, AuthUserPayload::fromAuthUser($creator), $occurredAt);
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
        return User::create()->modifyByArray([
            UserId::class => $this->getUserId(),
            Username::class => $this->getUsername(),
            EmailAddress::class => $this->getEmailAddress(),
            Password::class => $this->getPassword(),
            RoleId::class => $this->getRoleId(),
            CreatedAt::class => CreatedAt::fromDateTime($this->getOccurredAt()->toDateTime()),
            VerifiedAt::class => $this->getVerifiedAt(),
        ]);
    }

    private function getUserId(): UserId
    {
        return UserId::fromString($this->findResourceId()->toString());
    }

    private function getResourceChange(): Payload\ResourceChange
    {
        return $this->getPayload()->findResourceChange(self::findResourceTypeId(), $this->findResourceId());
    }

    private function getUsername(): Username
    {
        $value = $this->getResourceChange()->findAttributeChange(self::USERNAME_KEY)->getValue();
        return Username::fromString($value);
    }

    private function getRoleId(): RoleId
    {
        $value = $this->getResourceChange()->findAttributeChange(self::ROLE_ID_KEY)->getValue();
        return RoleId::fromString($value);
    }

    private function getEmailAddress(): EmailAddress
    {
        $value = $this->getResourceChange()->findAttributeChange(self::EMAIL_ADDRESS_KEY)->getValue();
        return EmailAddress::fromString($value);
    }

    private function getPassword(): Password
    {
        $value = $this->getResourceChange()->findAttributeChange(self::PASSWORD_HASH_KEY)->getValue();
        return Password::fromHash($value);
    }

    private function getVerifiedAt(): VerifiedAt
    {
        $value = $this->getResourceChange()->findAttributeChange(self::VERIFIED_AT)->getValue();
        return VerifiedAt::fromNullableString($value);
    }
}