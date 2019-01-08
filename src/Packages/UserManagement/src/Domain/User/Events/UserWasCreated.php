<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Events;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\DateTimeFactory;
use App\Packages\Common\Domain\Event\AbstractEvent;
use App\Packages\UserManagement\Application\Resources\User\User;
use App\Packages\UserManagement\Domain\User\UserPayloadConverter;

final class UserWasCreated extends AbstractEvent
{
    public static function occur(User $user, AuthUser $authUser): self
    {
        $previousPayload = null;
        $occurredOn = DateTimeFactory::create();
        $payload = UserPayloadConverter::convertToPayload($user, [
            'createdAt' => DateTimeFactory::createString($occurredOn)
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

    public  function getResourceClass(): ?string
    {
        return User::class;
    }
}