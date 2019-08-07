<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain;

use App\Packages\Common\Domain\Aggregate;
use App\Packages\Common\Domain\AuditLog\EventStream;
use App\Packages\UserManagement\Application\Command\User\UserParams;
use App\Packages\UserManagement\Application\Query\User\User;
use App\Packages\UserManagement\Domain\Events\UserWasCreated;
use App\Packages\UserManagement\Domain\Events\VerificationCodeWasSentToUser;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerificationCode;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;

final class UserAggregate extends Aggregate
{
    private $user;

    protected function __construct(User $user, EventStream $recordedEvents)
    {
        parent::__construct($recordedEvents);
        $this->user = $user;
    }

    public static function fromUserParams(UserParams $userParams): self
    {
        UserId::fromString($userParams->getUserId()),
        Username::fromString($userParams->getUsername()),
        EmailAddress::fromString($userParams->getEmailAddress()),
        Password::fromString($userParams->getPassword()),
        RoleId::fromString($userParams->getRoleId()),
        $command->getCommandExecutor()
    }

    public static function create(
        UserId $userId,
        Username $username,
        EmailAddress $emailAddress,
        Password $password,
        RoleId $roleId,
        AuthUser $creator
    ): self
    {
        return new self($userId, new EventStream([
            UserWasCreated::occur($userId, $username, $emailAddress, $password, $roleId, $creator),
        ]));
    }

    public function sendEmailAddressVerificationCode( //todo: use in handler
        EmailAddress $emailAddress,
        VerificationCode $verificationCode,
        AuthUser $sender
    ): void {
        $this->recordEvent(VerificationCodeWasSentToUser::occur($this->id, $emailAddress, $verificationCode, $sender));
    }
}