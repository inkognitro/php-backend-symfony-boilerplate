<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User;

use App\Packages\Common\Application\Authorization\RolesRepository;
use App\Packages\Common\Application\Resources\AbstractResource;
use App\Packages\Common\Application\Validation\Messages\DoesAlreadyExistMessage;
use App\Packages\Common\Application\Validation\Messages\DoesNotExistMessage;
use App\Packages\Common\Application\Validation\Messages\MessageBag;
use App\Packages\Common\Application\Validation\Rules\EmptyOrEmailAddressRule;
use App\Packages\Common\Application\Validation\Rules\EmptyOrUuidRule;
use App\Packages\Common\Application\Validation\Rules\NotEmptyRule;
use App\Packages\Common\Domain\AbstractResourceValidator;
use App\Packages\UserManagement\Application\Resources\User\User;
use App\Packages\UserManagement\Application\Resources\User\UserRepository;
use InvalidArgumentException;

final class UserValidator extends AbstractResourceValidator
{
    private $userRepository;
    private $rolesRepository;

    public function __construct(UserRepository $userRepository, RolesRepository $rolesRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->rolesRepository = $rolesRepository;
    }

    public function validate(AbstractResource $user): void
    {
        if(!$user instanceof User) {
            throw new InvalidArgumentException('Variable $user must be an instance of ' . User::class . '!');
        }
        $this->warnings = new MessageBag();
        $this->errors = new MessageBag();
        $this->validateUserId($user);
        $this->validateUsername($user);
        $this->validateEmailAddress($user);
        $this->validateRole($user);
    }

    private function validateUserId(User $user): void
    {
        $errorKey = 'id';

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
        $errorKey = 'username';

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
        $errorKey = 'emailAddress';

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
        $errorKey = 'role';

        $errorMessage = NotEmptyRule::getMessageFromValidation($user->getRole()->toString());
        if($errorMessage !== null) {
            $this->errors->addMessage($errorKey, $errorMessage);
            return;
        }

        if(!in_array($user->getRole()->toString(), $this->rolesRepository->findAll())) {
            $this->errors->addMessage($errorKey, new DoesNotExistMessage());
            return;
        }
    }
}