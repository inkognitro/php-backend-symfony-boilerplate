<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User;

use App\Packages\Common\Domain\EventDispatcher;
use App\Packages\Common\Domain\Projections;
use App\Packages\UserManagement\Domain\User\Attributes\Values\EmailAddress;
use App\Packages\UserManagement\Domain\User\Attributes\Values\UserId;
use App\Packages\UserManagement\Domain\User\Attributes\Values\Username;

final class UserRepository
{
    private $eventDispatcher;
    private $userProjection;

    public function findById(UserId $userId): ?User
    {
        return null; //todo!
    }

    public function findByUsername(Username $username): ?User
    {
        return null; //todo!
    }

    public function findByEmailAddress(EmailAddress $emailAddress): ?User
    {
        return null; //todo!
    }

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