<?php declare(strict_types=1);

namespace App\Resources\User\Application\Domain;

use App\Packages\Common\Application\Domain\Event\EventStream;
use App\Packages\Common\Application\Domain\Event\Payload;
use App\Resources\User\Application\Domain\Event\UserWasRemoved;
use App\Resources\User\Application\Attribute\EmailAddress;
use App\Resources\User\Application\Attribute\UserId;
use App\Resources\User\Application\Attribute\Username;
use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Resources\User\Application\User as QueryUser;

final class UserRepository
{
    private $recordedEvents;

    public function __construct()
    {
        $this->recordedEvents = new EventStream([]);
    }

    public function findById(UserId $id): ?QueryUser
    {
        return null; //todo with query class from injection
    }

    public function findByEmailAddress(EmailAddress $emailAddress): ?QueryUser
    {
        return null; //todo with query class from injection
    }

    public function save(User $user): void
    {
        $this->recordedEvents = $this->recordedEvents->getMerged($user->getRecordedEvents());
    }

    public function findByUsername(Username $username): ?QueryUser
    {
        return null; //todo with query class from injection
    }

    public function remove(QueryUser $user, AuthUser $authUser): void
    {
        $payload = Payload::fromData($user->toArray());
        $this->recordedEvents->getMerged(new EventStream([
            UserWasRemoved::occur($payload, $authUser)
        ]));
    }
}