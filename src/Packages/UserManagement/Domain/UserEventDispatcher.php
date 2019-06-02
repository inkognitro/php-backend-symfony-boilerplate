<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain;

use App\Packages\Common\Domain\AuditLog\EventDispatcher;
use App\Packages\Common\Domain\AuditLog\Projections;

final class UserEventDispatcher
{
    private $eventDispatcher;
    private $userEventProjection;

    public function __construct(EventDispatcher $eventDispatcher, UserEventProjection $userEventProjection)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userEventProjection = $userEventProjection;
    }

    public function dispatchEventsFromUserAggregate(UserAggregate $user): void
    {
        $this->eventDispatcher->dispatch(
            $user->getRecordedEvents(),
            Projections::fromArray([
                $this->userEventProjection,
            ])
        );
    }
}