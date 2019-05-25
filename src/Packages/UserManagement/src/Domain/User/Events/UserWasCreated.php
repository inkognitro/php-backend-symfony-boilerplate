<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Events;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Domain\Event\AbstractEvent;
use App\Packages\Common\Domain\Event\EventId;
use App\Packages\Common\Domain\Event\OccurredAt;
use App\Packages\UserManagement\Domain\User\User;

final class UserWasCreated extends AbstractEvent
{
    public static function occur(User $user, AuthUser $authUser): self
    {
        $previousPayload = null;
        $occurredAt = OccurredAt::create();
        $payload = UserPayload::fromUser($user, [
            'createdAt' => $occurredAt->toString()
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