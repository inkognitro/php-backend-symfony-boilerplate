<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User;

use App\Packages\Common\Application\Resources\AbstractResource;
use App\Packages\Common\Application\Validation\Messages\DoesAlreadyExistMessage;
use App\Packages\Common\Application\Validation\Messages\MessageBag;
use App\Packages\Common\Application\Validation\Messages\Rules\EmptyOrEmailAddressRule;
use App\Packages\Common\Application\Validation\Messages\Rules\EmptyOrUuidRule;
use App\Packages\Common\Application\Validation\Messages\Rules\NotEmptyRule;
use App\Packages\Common\Domain\ResourceValidator;
use App\Packages\UserManagement\Application\Resources\User\EmailAddress;
use App\Packages\UserManagement\Application\Resources\User\User;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Application\Resources\User\Username;
use App\Packages\UserManagement\Application\Resources\User\UserRepository;
use InvalidArgumentException;

final class UserValidator extends ResourceValidator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    public function validate(AbstractResource $user): void
    {
        if(!$user instanceof User) {
            throw new InvalidArgumentException('Variable $resource must be an instance of ' . User::class . '!');
        }
        $this->warnings = new MessageBag();
        $this->errors = new MessageBag();
        $this->validateUserId($user);
        $this->validateUsername($user);
    }

    private function validateFormat(array $userData): void
    {
        $this->validateUserId()

        $attributeToRulesMapping = [
            UserId => [
                NotEmptyRule::class,
                EmptyOrUuidRule::class,
            ],
            Username::NAME => [
                NotEmptyRule::class,
                EmptyOrEmailAddressRule::class,
            ],
            EmailAddress::NAME => [
                NotEmptyRule::class,
                EmptyOrEmailAddressRule::class,
            ],
        ];
        foreach ($attributeToRulesMapping as $attributeName => $rules) {
            $message = $this->validator->getMessageFromValidation($userData, $rules);
            if ($message !== null) {
                $this->errors->addMessage($attributeName, $message);
            }
        }
    }

    private function validateUserId(User $user): void
    {
        $errorMessage = NotEmptyRule::getMessageFromValidation($user->getId());
        if($errorMessage !== null) {
            $this->errors->addMessage('id', $errorMessage);
            return;
        }

        $errorMessage = EmptyOrUuidRule::getMessageFromValidation($user->getId());
        if($errorMessage !== null) {
            $this->errors->addMessage('id', $errorMessage);
        }
    }

    private function validateUsername(User $user): void
    {
        $errorKey = 'username';

        $errorMessage = NotEmptyRule::getMessageFromValidation($user->getId());
        if($errorMessage !== null) {
            $this->errors->addMessage($errorKey, $errorMessage);
            return;
        }

        if ($this->isValidUniqueUserDataByKey($userData, Username::NAME)) {
            return;
        }
        $this->errors->addMessage($errorKey, new DoesAlreadyExistMessage());
    }

    private function validateUniqueEmailAddress(array $userData): void
    {
        if ($this->isValidUniqueUserDataByKey($userData, EmailAddress::NAME)) {
            return;
        }
        $this->errors->addMessage(EmailAddress::NAME, new DoesAlreadyExistMessage());
    }
}