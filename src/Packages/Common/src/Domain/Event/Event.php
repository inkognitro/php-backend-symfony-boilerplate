<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Event;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use DateTimeImmutable;

interface Event
{
    public function getOccurredOn(): DateTimeImmutable;
    public function getTriggeredFrom(): AuthUser;
    public function mustBeLogged(): bool;
    public function getPayload(): Payload;
    public function getPreviousPayload(): ?Payload;
}