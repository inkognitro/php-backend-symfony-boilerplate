<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User;

use App\Packages\AccessManagement\Application\Role\RoleId;
use App\Packages\Common\Application\Authorization\RolesRepository;
use App\Packages\Common\Application\Validation\Messages\DoesAlreadyExistMessage;
use App\Packages\Common\Application\Validation\Messages\DoesNotExistMessage;
use App\Packages\Common\Application\Validation\Messages\MessageBag;
use App\Packages\Common\Application\Validation\Rules\EmptyOrEmailAddressRule;
use App\Packages\Common\Application\Validation\Rules\EmptyOrUuidRule;
use App\Packages\Common\Application\Validation\Rules\NotEmptyRule;
use App\Packages\Common\Domain\Validator;
use App\Packages\UserManagement\Domain\User\Attributes\Values\EmailAddress;
use App\Packages\UserManagement\Domain\User\Attributes\Values\UserId;
use App\Packages\UserManagement\Domain\User\Attributes\Values\Username;

final class UserAttributeFormatsValidator extends Validator
{
    private $userRepository;
    private $rolesRepository;

    public function validate(User $user): void
    {
        $this->warnings = new MessageBag();
        $this->errors = new MessageBag();
        $this->validateUserId($user);
        $this->validateUsername($user);
        $this->validateEmailAddress($user);
        $this->validateRole($user);
    }

    private function validateUserId(User $user): void
    {
        $errorKey = UserId::KEY;

        $errorMessage = NotEmptyRule::getMessageFromValidation($user->getId()->toString());
        if($errorMessage !== null) {
            $this->errors->addMessage($errorKey, $errorMessage);
            return;
        }

        $errorMessage = EmptyOrUuidRule::getMessageFromValidation($user->getId()->toString());
        if($errorMessage !== null) {
            $this->errors->addMessage($errorKey, $errorMessage);
        }
    }

    private function validateUsername(User $user): void
    {
        $errorKey = Username::KEY;

        $errorMessage = NotEmptyRule::getMessageFromValidation($user->getUsername()->toString());
        if($errorMessage !== null) {
            $this->errors->addMessage($errorKey, $errorMessage);
            return;
        }

        $foundUser = $this->userRepository->findByUsername($user->getUsername());
        if($foundUser !== null && !$foundUser->getId()->isEqual($user->getId())) {
            $this->errors->addMessage($errorKey, new DoesAlreadyExistMessage());
        }
    }

    private function validateEmailAddress(User $user): void
    {
        $errorKey = EmailAddress::KEY;

        $errorMessage = NotEmptyRule::getMessageFromValidation($user->getEmailAddress()->toString());
        if($errorMessage !== null) {
            $this->errors->addMessage($errorKey, $errorMessage);
            return;
        }

        $errorMessage = EmptyOrEmailAddressRule::getMessageFromValidation($user->getEmailAddress()->toString());
        if($errorMessage !== null) {
            $this->errors->addMessage($errorKey, $errorMessage);
            return;
        }

        $foundUser = $this->userRepository->findByEmailAddress($user->getEmailAddress());
        if($foundUser !== null && !$foundUser->getId()->isEqual($user->getId())) {
            $this->errors->addMessage($errorKey, new DoesAlreadyExistMessage());
        }
    }

    private function validateRole(User $user): void
    {
        $errorKey = RoleId::KEY;

        $errorMessage = NotEmptyRule::getMessageFromValidation($user->getRoleId()->toString());
        if($errorMessage !== null) {
            $this->errors->addMessage($errorKey, $errorMessage);
            return;
        }

        if(!in_array($user->getRoleId()->toString(), $this->rolesRepository->findAll())) {
            $this->errors->addMessage($errorKey, new DoesNotExistMessage());
            return;
        }
    }
}