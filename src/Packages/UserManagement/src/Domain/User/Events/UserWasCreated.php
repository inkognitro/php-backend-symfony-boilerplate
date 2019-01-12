<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Events;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\Resources\Events\AbstractEvent;
use App\Packages\Common\Application\Resources\Events\OccurredOn;
use App\Packages\UserManagement\Application\Resources\User\User;
use App\Packages\UserManagement\Domain\User\UserPayloadConverter;

final class UserWasCreated extends AbstractEvent
{
    public static function occur(User $user, AuthUser $authUser): self
    {
        $previousPayload = null;
        $occurredOn = OccurredOn::fromNow();
        $payload = UserPayloadConverter::convertToPayload($user, [
            'createdAt' => $occurredOn->toString()
        ]);
        return new self($occurredOn, $authUser, $payload, $previousPayload);
    }

    public function getUser(): User
    {
        return UserPayloadConverter::convertToUser($this->getPayload());
    }

    public function mustBeLogged(): bool
    {
        return true;
    }
}