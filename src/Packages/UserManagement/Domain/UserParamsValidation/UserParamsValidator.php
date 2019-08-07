<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\User;

use App\Packages\Common\Application\Command\Params\Text;
use App\Packages\Common\Utilities\Validation\Messages\MustNotBeEmptyMessage;
use App\Packages\UserManagement\Application\Query\User\UsersQueryHandler;
use App\Packages\Common\Utilities\Validation\Messages\CanNotBeChosenMessage;
use App\Packages\Common\Utilities\Validation\Messages\DoesAlreadyExistMessage;
use App\Packages\Common\Utilities\Validation\Rules\RequiredEmailAddressRule;
use App\Packages\Common\Utilities\Validation\Rules\RequiredUuidRule;
use App\Packages\Common\Utilities\Validation\Rules\TextRule;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;
use App\Packages\Common\Utilities\Validation\ValidationResult;

final class UserParamsValidator
{
    private $usersQueryHandler;

    public function __construct(UsersQueryHandler $usersQueryHandler)
    {
        $this->usersQueryHandler = $usersQueryHandler;
    }

    public function validateCreation(UserParams $params): ValidationResult
    {
        $this->validateUserId($params->getId());

        $isRequired = true;
        $this->validateUsername($params->getUsername(), $isRequired);


        $this->validateEmailAddressFormat($command->getEmailAddress());
        $this->validateRoleId($command->getRoleId(), $command->getExecutor());
        $this->validatePassword($command->getPassword());
        $validateForExistingUser = false;
        $this->validateExistingUsers(
            $command->getUserId(), $command->getUsername(), $command->getEmailAddress(), $validateForExistingUser
        );
    }

    private function validateUserId(?Text $userId): ValidationResult
    {
        $validationResult = ValidationResult::create();
        if($userId === null) {
            return $validationResult->addFieldErrorMessage(UserId::class, new MustNotBeEmptyMessage());
        }
        $error = RequiredUuidRule::findError($userId);
        if ($error !== null) {
            $validationResult = $validationResult->addFieldErrorMessage(UserId::class, $error);
        }
        return $validationResult;
    }

    private function validateUsername(?Text $username, bool $isRequired): ValidationResult
    {
        $validationResult = ValidationResult::create();
        if(!$isRequired && $username === null) {
            return $validationResult;
        }
        if($username === null) {
            return $validationResult->addFieldErrorMessage(Username::class, new MustNotBeEmptyMessage());
        }
        $rule = TextRule::create()->setMinLength(6)->setMaxLength(20)->setAllowedCharsRegex('/[^A-Za-z0-9]/');
        $error = $rule->findError($username->toString());
        if ($error !== null) {
            return $validationResult->addFieldErrorMessage(Username::class, $error);
        }
        return $validationResult;
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