<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Events;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Domain\Event\AbstractAuditLogEvent;
use App\Packages\Common\Domain\Event\EventId;
use App\Packages\Common\Domain\Event\OccurredAt;
use App\Packages\UserManagement\Domain\User\User;
use App\Resources\AuditLogEvent\Payload;
use App\Resources\User\CreatedAt;
use App\Resources\User\EmailAddress;
use App\Resources\User\UserId;
use App\Resources\User\Username;

final class UserWasCreated extends AbstractAuditLogEvent
{
    public static function occur(
        UserId $userId,
        Username $username,
        EmailAddress $emailAddress,
        AuthUser $authUser
    ): self {
        $previousPayload = null;
        $occurredAt = OccurredAt::create();
        $payload = Payload::fromArray($user, [
            CreatedAt::getKey() => $occurredAt->toString()
        ]);
        return new self(EventId::create(), $occurredAt, $authUser, $payload, $previousPayload);
    }

    public function getUser(): User
    {
        /** @var $payload UserPayload */
        $payload = $this->getPayload();
        return $payload->toUser();
    }

    public function mustBeLogged(): bool
    {
        return true;
    }
}