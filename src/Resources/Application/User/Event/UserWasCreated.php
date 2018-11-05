<?php declare(strict_types=1);

namespace App\Resources\Application\Application\User\Event;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\Event\AbstractEvent;
use App\Packages\Common\Application\CommandHandling\Event\Payload;
use DateTimeImmutable;

final class UserWasCreated extends AbstractEvent
{
    public static function occur(Payload $payload, AuthUser $authUser): self
    {
        $previousPayload = null;
        return new self(
            new DateTimeImmutable('now'), $authUser, $payload, $previousPayload
        );
    }

    public function mustBeLogged(): bool
    {
        return true;
    }
}