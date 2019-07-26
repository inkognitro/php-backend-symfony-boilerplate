<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\Events;

use App\Packages\Common\Domain\AuditLog\AuditLogEvent;
use App\Resources\Application\AuditLogEvent\Attributes\EventId;
use App\Resources\Application\AuditLogEvent\Attributes\OccurredAt;
use App\Resources\Application\AuditLogEvent\Attributes\Payload;
use App\Resources\Application\AuditLogEvent\Attributes\ResourceId;
use App\Resources\Application\AuditLogEvent\Attributes\ResourceType;
use App\Resources\Application\User\Attributes\EmailAddress;
use App\Resources\Application\User\Attributes\User;
use App\Resources\Application\User\Attributes\UserId;
use App\Resources\Application\User\Attributes\VerificationCode;
use App\Utilities\Authentication\AuthUser;

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
            EventId::create(), $resourceId, $payload, $sender->toAuditLogEventAuthUserPayload(), $occurredAt
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

    public static function getResourceType(): ResourceType
    {
        return ResourceType::fromString(User::class);
    }
}