<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain;

use App\Packages\Common\Domain\Aggregate;
use App\Packages\Common\Domain\AuditLog\EventStream;
use App\Packages\UserManagement\Domain\Events\UserWasCreated;
use App\Packages\UserManagement\Domain\Events\VerificationCodeWasSentToUser;
use App\Resources\Application\User\Attributes\EmailAddress;
use App\Resources\Application\User\Attributes\Password;
use App\Resources\Application\User\Attributes\UserId;
use App\Resources\Application\User\Attributes\Username;
use App\Resources\Application\User\Attributes\VerificationCode;
use App\Resources\Application\Role\Attributes\RoleId;
use App\Utilities\Authentication\AuthUser as AuthUser;

final class UserAggregate extends Aggregate
{
    private $id;

    protected function __construct(UserId $userId, EventStream $recordedEvents)
    {
        parent::__construct($recordedEvents);
        $this->id = $userId;
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