<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Events;

use App\Packages\Common\Domain\Event\AuditLogEvent;
use App\Resources\AuditLogEvent\EventId;
use App\Resources\AuditLogEvent\OccurredAt;
use App\Resources\AuditLogEvent\Payload;
use App\Resources\AuditLogEvent\ResourceId;
use App\Resources\User\User;
use App\Resources\User\UserId;
use App\Resources\User\VerificationCode;
use App\Utilities\AuthUser;

final class VerificationCodeWasSentToUser extends AuditLogEvent
{
    public static function occur(
        VerificationCode $verificationCode,
        UserId $userId,
        AuthUser $sender
    ): self {
        $occurredAt = OccurredAt::create();
        $payload = Payload::fromArray([
            VerificationCode::getKey() => $verificationCode->toString()
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

    public function getVerificationCode(): VerificationCode
    {
        $verificationCode = $this->getPayload()->toArray()[VerificationCode::getKey()];
        return VerificationCode::fromString($verificationCode);
    }

    public function mustBeLogged(): bool
    {
        return true;
    }

    public  static function getResourceType(): string
    {
        return User::class;
    }
}