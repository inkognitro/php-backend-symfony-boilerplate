<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\Resources\Events\EventStream;
use App\Packages\Common\Domain\AbstractAggregate;
use App\Packages\UserManagement\Application\Resources\User\Events\UserWasCreated;
use App\Packages\UserManagement\Application\Resources\User\User;

final class UserAggregate extends AbstractAggregate
{
    private $persistedUser;
    private $currentUser;

    protected function __construct(EventStream $recordedEvents, ?User $persistedJob, User $currentJob)
    {
        parent::__construct($recordedEvents);
        $this->currentUser = $currentJob;
        $this->persistedUser = $persistedJob;
    }

    public function getUser(): User
    {
        return $this->currentUser;
    }

    public static function fromNewUser(User $user, AuthUser $creator): self
    {
        $persistedUser = null;
        $event = UserWasCreated::occur($user, $creator);
        $createdUser = $event->getUser();
        return new self(new EventStream([$event]), $persistedUser, $createdUser);
    }

    public static function fromExistingUser(User $user): self
    {
        $persistedUser = null;
        return new self(new EventStream([]), $persistedUser, $user);
    }

    //todo getRecoredEvents(): compare persisted and current user, maybe no event should be triggered!
}