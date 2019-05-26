<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain;

use App\Packages\Common\Domain\AuditLog\EventDispatcher;
use App\Packages\Common\Domain\AuditLog\Projections;
use App\Packages\UserManagement\Domain\UserAggregate;

final class UserRepository
{
    private $eventDispatcher;
    private $userProjection;

    public function __construct(EventDispatcher $eventDispatcher, UserProjection $userProjection)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userProjection = $userProjection;
    }

    public function save(UserAggregate $user): void
    {
        $this->eventDispatcher->dispatch(
            $user->getRecordedEvents(),
            Projections::fromArray([
                $this->userProjection,
            ])
        );
    }
}