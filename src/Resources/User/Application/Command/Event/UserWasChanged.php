<?php declare(strict_types=1);

namespace App\Resources\User\Application\Application\User\Command\Event;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\Event\AbstractEvent;
use App\Packages\Common\Application\CommandHandling\Event\Payload;
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
}