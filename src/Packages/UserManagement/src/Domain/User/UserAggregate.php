<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Domain\Event\EventStream;
use App\Packages\Common\Domain\Aggregate;
use App\Packages\UserManagement\Domain\User\Events\UserWasCreated;
use App\Packages\UserManagement\Application\Resources\User\User;

final class UserAggregate extends Aggregate
{
    private $persistedUser;
    private $currentUser;

    protected function __construct(EventStream $recordedEvents, ?User $persistedUser, User $currentUser)
    {
        parent::__construct($recordedEvents);
        $this->persistedUser = $persistedUser;
        $this->currentUser = $currentUser;
    }

    public static function fromUser(User $user): self
    {
        return new self(new EventStream([]), $user, $user);
    }

    public function toUser(): User
    {
        return $this->currentUser;
    }

    public static function create(User $userToCreate, AuthUser $authUser): self
    {
        $persistedUser = null;
        return new self(
            new EventStream([
                UserWasCreated::occur($userToCreate, $authUser)
            ]),
            $persistedUser,
            $userToCreate
        );
    }
}