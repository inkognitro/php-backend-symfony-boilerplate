<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain;

use App\Packages\Common\Domain\AuditLog\EventStream;
use App\Resources\User\EmailAddress;
use App\Resources\User\Password;
use App\Resources\User\UserId;
use App\Resources\User\Username;
use App\Resources\UserRole\RoleId;
use App\Utilities\AuthUser as AuthUser;
use App\Packages\Common\Domain\Aggregate;
use App\Packages\UserManagement\Domain\Events\UserWasCreated;
use App\Resources\User\User as UserResource;

final class UserAggregate extends Aggregate implements UserResource
{
    protected function __construct(EventStream $recordedEvents)
    {
        parent::__construct($recordedEvents);
    }

    public static function create(
        UserId $userId,
        Username $username,
        EmailAddress $emailAddress,
        Password $password,
        RoleId $roleId,
        AuthUser $creator
    ): self {
        $persistedUser = null;
        $events = [
            UserWasCreated::occur($userId, $username, $emailAddress, $password, $roleId, $creator),
        ];
        return new self(new EventStream($events));
    }

    //todo
    /*
    public function sendVerificationCode(VerificationCode $verificationCode, AuthUser $sender): void
    {
        $this->recordedEvents->record(
            VerificationCodeWasSentToUser::occur($verificationCode, $this->currentUser, $sender)
        );
    }
    */
}