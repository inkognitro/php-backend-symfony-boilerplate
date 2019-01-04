<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Events;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Domain\Event\AbstractEvent;
use App\Packages\UserManagement\Application\Resources\User\User;
use App\Packages\UserManagement\Domain\User\UserPayloadConverter;
use DateTimeImmutable;

final class UserWasCreated extends AbstractEvent
{
    public static function occur(User $user, AuthUser $authUser): self
    {
        $previousPayload = null;
        $payload = UserPayloadConverter::convertToPayload($user);
        return new self(new DateTimeImmutable('now'), $authUser, $payload, $previousPayload);
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