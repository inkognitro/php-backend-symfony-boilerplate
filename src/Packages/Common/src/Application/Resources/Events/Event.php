<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Resources\Events;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;

interface Event
{
    public function getOccurredOn(): OccurredOn;
    public function getTriggeredFrom(): AuthUser;
    public function mustBeLogged(): bool;
    public function getPayload(): Payload;
    public function getPreviousPayload(): ?Payload;
}