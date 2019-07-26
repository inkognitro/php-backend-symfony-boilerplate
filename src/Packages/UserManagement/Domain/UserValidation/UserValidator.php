<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\UserValidation;

use App\Utilities\Validation\Messages\CanNotBeChosenMessage;
use App\Utilities\Validation\Messages\DoesAlreadyExistMessage;
use App\Utilities\Validation\Rules\MaxLengthRule;
use App\Utilities\Validation\Rules\MinLengthRule;
use App\Utilities\Validation\Rules\RequiredEmailAddressRule;
use App\Utilities\Validation\Rules\RequiredUuidRule;
use App\Utilities\Validation\Rules\RequiredStringRule;
use App\Utilities\Validation\Validator;
use App\Packages\UserManagement\Application\CreateUser;
use App\Resources\Application\User\Attributes\EmailAddress;
use App\Resources\Application\User\Attributes\Password;
use App\Resources\Application\User\Attributes\UserId;
use App\Resources\Application\User\Attributes\Username;
use App\Resources\Application\Role\Attributes\RoleId;
use App\Utilities\Authentication\AuthUser;

final class UserValidator extends Validator
{
    private $usersWithAnEqualValueQuery;

    public function __construct(UsersWithAnEqualValueQuery $usersWithAnEqualValueQuery)
    {
        parent::__construct();
        $this->usersWithAnEqualValueQuery = $usersWithAnEqualValueQuery;
    }

    public function validateCreation(CreateUser $command): void
    {
        $this->resetMessageBags();
        $this->validateUserIdFormat($command->getUserId());
        $this->validateUsernameFormat($command->getUsername());
        $this->validateEmailAddressFormat($command->getEmailAddress());
        $this->validateRoleId($command->getRoleId(), $command->getExecutor());
        $this->validatePassword($command->getPassword());
        $validateForExistingUser = false;
        $this->validateExistingUsers(
            $command->getUserId(), $command->getUsername(), $command->getEmailAddress(), $validateForExistingUser
        );
    }

    private function validateUserIdFormat(string $userId): void
    {
        $error = RequiredUuidRule::findError($userId);
        if ($error !== null) {
            $this->errors->addMessage(UserId::getPayloadKey(), $error);
            return;
        }
    }

    private function validateUsernameFormat(string $username): void //todo: validate a-zA-Z_
    {
        $errorMessage = RequiredStringRule::findError($username);
        if ($errorMessage !== null) {
            $this->errors->addMessage(Username::getPayloadKey(), $errorMessage);
            return;
        }

        $minLength = 4;
        $errorMessage = MinLengthRule::findError($username, $minLength);
        if ($errorMessage !== null) {
            $this->errors->addMessage(Username::getPayloadKey(), $errorMessage);
            return;
        }

        $maxLength = 32;
        $errorMessage = MaxLengthRule::findError($username, $maxLength);
        if ($errorMessage !== null) {
            $this->errors->addMessage(Username::getPayloadKey(), $errorMessage);
        }
    }

    private function validateEmailAddressFormat(string $emailAddress): void
    {
        $errorMessage = RequiredEmailAddressRule::findError($emailAddress);
        if ($errorMessage !== null) {
            $this->errors = $this->errors->addMessage(EmailAddress::getPayloadKey(), $errorMessage);
            return;
        }

        $minLength = 4;
        $errorMessage = MinLengthRule::findError($emailAddress, $minLength);
        if ($errorMessage !== null) {
            $this->errors = $this->errors->addMessage(Username::getPayloadKey(), $errorMessage);
            return;
        }

        $maxLength = 191;
        $errorMessage = MaxLengthRule::findError($emailAddress, $maxLength);
        if ($errorMessage !== null) {
            $this->errors = $this->errors->addMessage(Username::getPayloadKey(), $errorMessage);
        }
    }

    private function validateRoleId(string $roleId, AuthUser $authUser): void
    {
        $errorMessage = RoleId::findFormatError($roleId);
        if ($errorMessage !== null) {
            $this->errors = $this->errors->addMessage(RoleId::getPayloadKey(), $errorMessage);
            return;
        }
        $availableRoleIds = [RoleId::user()->toString()];
        if ($authUser->isAdmin() || $authUser->isSystem()) {
            $availableRoleIds[] = RoleId::admin()->toString();
        }
        if (!in_array($roleId, $availableRoleIds)) {
            $this->errors = $this->errors->addMessage(RoleId::getPayloadKey(), new CanNotBeChosenMessage());
        }
    }

    private function validatePassword(string $password): void
    {
        $errorMessage = Password::findFormatError($password);
        if ($errorMessage !== null) {
            $this->errors = $this->errors->addMessage(Password::getPayloadKey(), $errorMessage);
        }
    }

    private function validateExistingUsers(
        string $userId,
        string $username,
        string $emailAddress,
        bool $validateForExistingUser
    ): void
    {
        if ($this->errors->hasOneOfKeys([UserId::getPayloadKey(), Username::getPayloadKey(), EmailAddress::getPayloadKey()])) {
            return;
        }
        $userIdToUse = UserId::fromString($userId);
        $usernameToUse = Username::fromString($username);
        $emailAddressToUse = EmailAddress::fromString($emailAddress);
        $users = $this->usersWithAnEqualValueQuery->execute($userIdToUse, $usernameToUse, $emailAddressToUse);
        foreach ($users->toArray() as $user) {
            if ($validateForExistingUser && $user->getUserId()->isEqual($userIdToUse)) {
                continue;
            }
            if (!$validateForExistingUser && $user->getUserId()->isEqual($userIdToUse)) {
                $this->errors = $this->errors->addMessage(UserId::getPayloadKey(), new DoesAlreadyExistMessage());;
            }
            if ($user->getUsername()->isEqual($usernameToUse)) {
                $this->errors = $this->errors->addMessage(Username::getPayloadKey(), new DoesAlreadyExistMessage());
            }
            if ($user->getEmailAddress()->isEqual($emailAddressToUse)) {
                $this->errors = $this->errors->addMessage(EmailAddress::getPayloadKey(), new DoesAlreadyExistMessage());
            }
        }
    }
}