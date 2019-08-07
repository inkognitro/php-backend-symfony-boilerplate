<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\Events;

use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\AuthUserPayload;
use App\Packages\Common\Domain\AuditLog\AuditLogEvent;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\EventId;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\OccurredAt;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\Payload;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceId;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceTypeId;
use App\Packages\UserManagement\Application\Query\User\User;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerificationCode;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;

final class VerificationCodeWasSentToUser extends AuditLogEvent
{
    public static function occur(
        UserId $userId,
        EmailAddress $emailAddress,
        VerificationCode $verificationCode,
        AuthUser $sender
    ): self {
        $occurredAt = OccurredAt::create();
        $payload = Payload::fromArray([
            EmailAddress::getPayloadKey() => $emailAddress->toString(),
            VerificationCode::getPayloadKey() => $verificationCode->toNullableString(),
        ]);
        $resourceId = ResourceId::fromString($userId->toString());
        return new self(
            EventId::create(), $resourceId, $payload, AuthUserPayload::fromAuthUser($sender), $occurredAt
        );
    }

    public function getUserId(): UserId
    {
        return UserId::fromString($this->getResourceId()->toString());
    }

    public function getEmailAddress(): EmailAddress
    {
        $emailAddress = $this->getPayload()->toArray()[EmailAddress::getPayloadKey()];
        return EmailAddress::fromString($emailAddress);
    }

    public function getVerificationCode(): VerificationCode
    {
        $verificationCode = $this->getPayload()->toArray()[VerificationCode::getPayloadKey()];
        return VerificationCode::fromNullableString($verificationCode);
    }

    public function mustBeLogged(): bool
    {
        return true;
    }

    public static function getResourceType(): ResourceTypeId
    {
        return ResourceTypeId::fromString(User::class);
    }
}