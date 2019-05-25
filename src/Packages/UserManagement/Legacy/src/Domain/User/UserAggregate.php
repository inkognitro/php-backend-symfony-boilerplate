<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User;

use App\Utilities\AuthUser as AuthUser;
use App\Packages\Common\Domain\Event\EventStream;
use App\Packages\Common\Domain\AbstractAggregate;
use App\Packages\UserManagement\Domain\User\Events\UserWasCreated;
use App\Packages\UserManagement\Domain\User\Events\VerificationCodeWasSentToUser;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerificationCode;

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

    public function sendVerificationCode(VerificationCode $verificationCode, AuthUser $sender): void
    {
        $this->recordedEvents->record(
            VerificationCodeWasSentToUser::occur($verificationCode, $this->currentUser, $sender)
        );
    }

    //todo getRecoredEvents(): compare persisted and current user, maybe no event should be triggered!
}