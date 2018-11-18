<?php declare(strict_types=1);

namespace App\Resources\User\Application\Command;

use App\Packages\Common\Application\Validation\Messages\DoesAlreadyExistMessage;
use App\Packages\Common\Application\Validation\Rule\EmptyOrEmailAddressRule;
use App\Packages\Common\Application\Validation\Rule\EmptyOrUuidRule;
use App\Packages\Common\Application\Validation\Rule\NotEmptyRule;
use App\Packages\Common\Application\Validation\Validator;
use App\Resources\Common\Application\Command\ResourceDataValidator;
use App\Resources\User\Application\Attribute\EmailAddress;
use App\Resources\User\Application\Attribute\UserId;
use App\Resources\User\Application\Attribute\Username;
use App\Resources\User\Application\UserRepository;

final class UserDataValidator extends ResourceDataValidator
{
    private $validator;
    private $userRepository;

    public function __construct(Validator $validator, UserRepository $userRepository)
    {
        parent::__construct();
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    protected function validateData(array $userData): void
    {
        $this->validateFormat($userData);
        $this->validateUniqueUsername($userData);
        $this->validateUniqueEmailAddress($userData);
    }

    private function validateFormat(array $userData): void
    {
        $attributeToRulesMapping = [
            UserId::NAME => [
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

    private function validateUniqueUsername(array $userData): void
    {
        if ($this->isValidUniqueUserDataByKey($userData, Username::NAME)) {
            return;
        }
        $this->errors->addMessage(Username::NAME, new DoesAlreadyExistMessage());
    }

    private function validateUniqueEmailAddress(array $userData): void
    {
        if ($this->isValidUniqueUserDataByKey($userData, EmailAddress::NAME)) {
            return;
        }
        $this->errors->addMessage(EmailAddress::NAME, new DoesAlreadyExistMessage());
    }

    private function isValidUniqueUserDataByKey(array $userData, string $key): bool
    {
        if (!isset($userData[$key])) {
            return true;
        }

        if ($this->errors->doesMessageKeyExist($key)) {
            return true;
        }

        $user = null;
        if ($key === EmailAddress::NAME) {
            $user = $this->userRepository->findByEmailAddress(
                EmailAddress::fromString((string)$userData[$key])
            );
        } else if ($key === Username::NAME) {
            $user = $this->userRepository->findByUsername(
                Username::fromString((string)$userData[$key])
            );
        }

        if ($user === null) {
            return true;
        }

        if(!isset($userData['id']) || strlen((string)$userData['id']) === 0) {
            return false;
        }

        $userId = UserId::fromString((isset($userData['id']) ? (string)$userData['id'] : ''));
        if ($user->getId()->equals($userId)) {
            return true;
        }

        return false;
    }
}