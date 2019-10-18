<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain;

use App\Packages\Common\Domain\Aggregate;
use App\Packages\Common\Domain\AuditLog\EventStream;
use App\Packages\UserManagement\Application\Query\User\User;
use App\Packages\UserManagement\Domain\Events\UserWasCreated;
use App\Packages\AccessManagement\Application\Query\AuthUser;

final class UserAggregate extends Aggregate
{
    private $storedUser;
    private $user;

    protected function __construct(?User $storedUser, User $user, EventStream $recordedEvents)
    {
        parent::__construct($recordedEvents);
        $this->storedUser = $storedUser;
        $this->user = $user;
    }

    public static function create(User $user, AuthUser $creator): self
    {
        $storedUser = null;
        $event = UserWasCreated::occur($user, $creator);
        return new self($storedUser, $event->getUser(), new EventStream([$event]));
    }

    public function toUser(): User
    {
        return $this->user;
    }
}