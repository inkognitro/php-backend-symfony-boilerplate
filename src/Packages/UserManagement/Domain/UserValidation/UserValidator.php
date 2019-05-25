<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\UserValidation;

use App\Packages\Common\Domain\Validation\Rules\MinLengthRule;
use App\Packages\Common\Domain\Validation\Rules\RequiredEmailAddressRule;
use App\Packages\Common\Domain\Validation\Rules\RequiredUuidRule;
use App\Packages\Common\Domain\Validation\Rules\NotEmptyRule;
use App\Packages\Common\Domain\Validator;
use App\Packages\UserManagement\Application\CreateUser;
use App\Resources\User\EmailAddress;
use App\Resources\User\Password;
use App\Resources\User\UserId;
use App\Resources\User\Username;
use App\Resources\UserRole\RoleId;

final class UserValidator extends Validator
{
    private $userQueryHandler;

    public function __construct(usersWithAnEqualValueQuery $userQueryHandler)
    {
        parent::__construct();
        $this->userQueryHandler = $userQueryHandler;
    }

    public function validateCreation(CreateUser $command): void
    {
        $this->validateUserIdFormat($command->getUserId());
        $this->validateUsernameFormat($command->getUsername());
        $this->validateEmailAddressFormat($command->getEmailAddress());
        $this->validateRoleId($command->getRoleId());
        $this->validatePassword($command->getPassword());
        $this->validateExistingUsers($command->getPassword()); //todo!
    }

    private function validateUserIdFormat(string $userId): void
    {
        $error = RequiredUuidRule::findError($userId);
        if($error !== null) {
            $this->errors->addMessage(UserId::getKey(), $error);
            return;
        }
    }

    private function validateUsernameFormat(string $username): void
    {
        $errorMessage = NotEmptyRule::findError($username);
        if($errorMessage !== null) {
            $this->errors->addMessage(Username::getKey(), $errorMessage);
        }
    }

    private function validateEmailAddressFormat(string $emailAddress): void
    {
        $errorMessage = RequiredEmailAddressRule::findError($emailAddress);
        if($errorMessage !== null) {
            $this->errors->addMessage(EmailAddress::getKey(), $errorMessage);
            return;
        }
    }

    private function validateRoleId(string $roleId): void
    {
        $errorMessage = RoleId::findFormatError($roleId);
        if($errorMessage !== null) {
            $this->errors->addMessage(RoleId::getKey(), $errorMessage);
        }
        //todo validate role exists!
    }

    private function validatePassword(string $password): void
    {
        $errorMessage = Password::findFormatError($password);
        if($errorMessage !== null) {
            $this->errors->addMessage(Password::getKey(), $errorMessage);
        }
    }
}