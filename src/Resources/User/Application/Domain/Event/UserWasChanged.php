<?php declare(strict_types=1);

namespace App\Resources\User\Application\Domain\Event;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\Domain\Event\AbstractEvent;
use App\Packages\Common\Application\Domain\Event\Payload;
use App\Resources\User\Application\User;
use DateTimeImmutable;

final class UserWasChanged extends AbstractEvent
{
    public static function occur(Payload $payload, Payload $previousPayload, AuthUser $authUser): self
    {
        return new self(
            new DateTimeImmutable('now'), $authUser, $payload, $previousPayload
        );
    }

    public function mustBeLogged(): bool
    {
        return true;
    }

    public  function getResourceClass(): string
    {
        return User::class;
    }
}