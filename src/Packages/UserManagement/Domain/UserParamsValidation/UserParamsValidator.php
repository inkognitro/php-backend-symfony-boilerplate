<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\UserParamsValidation;

use App\Packages\Common\Application\Command\Params\Text;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustNotBeEmptyMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\CanNotBeChosenMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\DoesAlreadyExistMessage;
use App\Packages\Common\Application\Utilities\Validation\Rules\RequiredEmailAddressRule;
use App\Packages\Common\Application\Utilities\Validation\Rules\RequiredUuidRule;
use App\Packages\Common\Application\Utilities\Validation\Rules\TextRule;
use App\Packages\UserManagement\Application\Command\User\UserParams;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;
use App\Packages\Common\Application\Utilities\Validation\ValidationResult;

final class UserParamsValidator
{
    private $existingUsersByValuesQueryHandler;

    public function __construct(ExistingUsersByValuesQueryHandler $existingUsersByValuesQueryHandler)
    {
        $this->existingUsersByValuesQueryHandler = $existingUsersByValuesQueryHandler;
    }

    public function validateCreation(UserParams $params, AuthUser $authUser): ValidationResult
    {
        $isRequired = true;
        $validationResult = $this->validateUserId($params->getId())
            ->merge($this->validateUsername($params->getUsername(), $isRequired))
            ->merge($this->validateEmailAddress($params->getEmailAddress(), $isRequired))
            ->merge($this->validateRoleId($params->getRoleId(), $authUser, $isRequired))
            ->merge($this->validatePassword($params->getPassword(), $isRequired));
        $isChange = false;
        return $validationResult->merge($this->validateExistingUsers($params, $validationResult, $isChange));
    }

    public function validateChange(UserParams $params, AuthUser $authUser): ValidationResult
    {
        $isRequired = false;
        $validationResult = $this->validateUserId($params->getId())
            ->merge($this->validateUsername($params->getUsername(), $isRequired))
            ->merge($this->validateEmailAddress($params->getEmailAddress(), $isRequired))
            ->merge($this->validateRoleId($params->getRoleId(), $authUser, $isRequired))
            ->merge($this->validatePassword($params->getPassword(), $isRequired));
        $isChange = true;
        return $validationResult->merge($this->validateExistingUsers($params, $validationResult, $isChange));
    }

    private function validateUserId(?Text $userId): ValidationResult
    {
        $validationResult = ValidationResult::create();
        if ($userId === null) {
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
        if (!$isRequired && $username === null) {
            return $validationResult;
        }
        if ($username === null) {
            return $validationResult->addFieldErrorMessage(Username::class, new MustNotBeEmptyMessage());
        }
        $rule = TextRule::create()->setMinLength(6)->setMaxLength(20)->setAllowedCharsRegex('/[^A-Za-z0-9]/');
        $error = $rule->findError($username->toString());
        if ($error !== null) {
            return $validationResult->addFieldErrorMessage(Username::class, $error);
        }
        return $validationResult;
    }

    private function validateEmailAddress(?Text $emailAddress, bool $isRequired): ValidationResult
    {
        $validationResult = ValidationResult::create();
        if (!$isRequired && $emailAddress === null) {
            return $validationResult;
        }
        if ($emailAddress === null) {
            return $validationResult->addFieldErrorMessage(EmailAddress::class, new MustNotBeEmptyMessage());
        }
        $rule = new RequiredEmailAddressRule();
        $error = $rule->findError($emailAddress->toString());
        if ($error !== null) {
            return $validationResult->addFieldErrorMessage(EmailAddress::class, $error);
        }
        $rule = $rule = TextRule::create()->setMinLength(4)->setMaxLength(191);
        $error = $rule->findError($emailAddress->toString());
        if ($error !== null) {
            return $validationResult->addFieldErrorMessage(EmailAddress::class, $error);
        }
        return $validationResult;
    }

    private function validateRoleId(?Text $roleId, AuthUser $authUser, bool $isRequired): ValidationResult
    {
        $validationResult = ValidationResult::create();
        if (!$isRequired && $roleId === null) {
            return $validationResult;
        }
        $rule = TextRule::create();
        $error = $rule->findError($roleId->toString());
        if ($error !== null) {
            return $validationResult->addFieldErrorMessage(RoleId::class, $error);
        }
        $availableRoleIds = [RoleId::user()->toString()];
        if ($authUser->isAdmin() || $authUser->isSystem()) {
            $availableRoleIds[] = RoleId::admin()->toString();
        }
        if (!in_array($roleId->toString(), $availableRoleIds)) {
            return $validationResult->addFieldErrorMessage(RoleId::class, new CanNotBeChosenMessage());
        }
        return $validationResult;
    }

    private function validatePassword(?Text $password, bool $isRequired): ValidationResult
    {
        $validationResult = ValidationResult::create();
        if (!$isRequired && $password === null) {
            return $validationResult;
        }
        if ($password === null) {
            return $validationResult->addFieldErrorMessage(Password::class, new MustNotBeEmptyMessage());
        }
        $rule = TextRule::create()->setMinLength(6)->setMaxLength(64);
        $error = $rule->findError($password->toString());
        if ($error !== null) {
            return $validationResult->addFieldErrorMessage(Password::class, $error);
        }
        return $validationResult;
    }

    private function validateExistingUsers(
        UserParams $userParams,
        ValidationResult $validationResult,
        bool $isChange
    ): ValidationResult
    {
        if ($validationResult->getFieldErrors()->hasKey(UserId::class)) {
            return $validationResult;
        }
        if ($validationResult->getFieldErrors()->hasKey(Username::class)) {
            return $validationResult;
        }
        if ($validationResult->getFieldErrors()->hasKey(EmailAddress::class)) {
            return $validationResult;
        }
        $userId = UserId::fromString(($userParams->getId()->toString()));
        $username = ($userParams->getUsername() === null ? null : Username::fromString(
            $userParams->getUsername()->toString()
        ));
        $emailAddress = ($userParams->getEmailAddress() === null ? null : EmailAddress::fromString(
            $userParams->getEmailAddress()->toString()
        ));
        $query = new ExistingUsersByValuesQuery($userId, $username, $emailAddress);
        $users = $this->existingUsersByValuesQueryHandler->handle($query);
        foreach ($users->toArray() as $user) {
            if ($isChange && $user->getUserId()->isEqual($user->getUserId())) {
                continue;
            }
            if (!$isChange && $user->getUserId()->isEqual($userId)) {
                $validationResult = $validationResult->addFieldErrorMessage(UserId::class, new DoesAlreadyExistMessage());
            }
            if ($user->getUsername()->isEqual($username)) {
                $validationResult = $validationResult->addFieldErrorMessage(Username::class, new DoesAlreadyExistMessage());
            }
            if ($user->getEmailAddress()->isEqual($emailAddress)) {
                $validationResult = $validationResult->addFieldErrorMessage(EmailAddress::class, new DoesAlreadyExistMessage());
            }
        }
        return $validationResult;
    }
}