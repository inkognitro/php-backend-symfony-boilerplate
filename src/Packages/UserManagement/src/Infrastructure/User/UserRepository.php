<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\User;

use App\Packages\Common\Domain\Event\EventStream;
use App\Packages\Common\Domain\Event\Payload;
use App\Packages\UserManagement\Domain\User\Event\UserWasRemoved;
use App\Packages\UserManagement\Application\Resources\User\EmailAddress;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Application\Resources\User\Username;
use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\UserManagement\Application\Resources\User\User;

final class UserRepository
{
    private $recordedEvents;

    public function __construct()
    {
        $this->recordedEvents = new EventStream([]);
    }

    public function findById(UserId $id): ?User
    {
        return null;
    }

    public function findByEmailAddress(EmailAddress $emailAddress): ?User
    {
        return null; //todo with query class from injection
    }

    public function save(UserAggregate $user): void
    {
        $this->recordedEvents = $this->recordedEvents->getMerged($user->getRecordedEvents());
    }

    public function findByUsername(Username $username): ?QueryUser
    {
        return null; //todo with query class from injection
    }

    public function remove(QueryUser $user, AuthUser $authUser): void
    {
        $payload = Payload::fromArray($user->toArray());
        $this->recordedEvents->getMerged(new EventStream([
            UserWasRemoved::occur($payload, $authUser)
        ]));
    }
}