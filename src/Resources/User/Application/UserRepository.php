<?php declare(strict_types=1);

namespace App\Resources\User\Application;

use App\Packages\Common\Application\CommandHandling\Event\EventStream;
use App\Packages\Common\Application\CommandHandling\Event\Payload;
use App\Resources\User\Application\Application\User\Command\Event\UserWasRemoved;
use App\Resources\User\Application\Command\CommandUser;
use App\Resources\User\Application\Attribute\EmailAddress;
use App\Resources\User\Application\Attribute\UserId;
use App\Resources\User\Application\Attribute\Username;
use App\Packages\Common\Application\Authorization\User as AuthUser;

final class UserRepository
{
    private $recordedEvents;

    public function __construct()
    {
        $this->recordedEvents = new EventStream([]);
    }

    public function findById(UserId $id): ?User
    {
        return null; //todo with query class from injection
    }

    public function findByEmailAddress(EmailAddress $emailAddress): ?User
    {
        return null; //todo with query class from injection
    }

    public function save(CommandUser $user): void
    {
        $this->recordedEvents = $this->recordedEvents->getMerged($user->getRecordedEvents());
    }

    public function findByUsername(Username $username): ?User
    {
        return null; //todo with query class from injection
    }

    public function remove(User $user, AuthUser $authUser): void
    {
        $payload = Payload::fromData($user->toArray());
        $this->recordedEvents->getMerged(new EventStream([
            UserWasRemoved::occur($payload, $authUser)
        ]));
    }
}