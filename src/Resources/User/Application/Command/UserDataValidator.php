<?php declare(strict_types=1);

namespace App\Resources\User\Application\Command;

use App\Packages\Common\Application\Validation\Messages\DoesAlreadyExistMessage;
use App\Packages\Common\Application\Validation\Rule\EmptyOrEmailAddressRule;
use App\Packages\Common\Application\Validation\Rule\EmptyOrUuidRule;
use App\Packages\Common\Application\Validation\Rule\NotEmptyRule;
use App\Packages\Common\Application\Validation\Validator;
use App\Resources\Common\Application\Command\ResourceDataValidator;
use App\Resources\User\Application\Property\EmailAddress;
use App\Resources\User\Application\Property\UserId;
use App\Resources\User\Application\Property\Username;
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
            'id' => [
                NotEmptyRule::class,
                EmptyOrUuidRule::class,
            ],
            'username' => [
                NotEmptyRule::class,
                EmptyOrEmailAddressRule::class,
            ],
            'emailAddress' => [
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
        if ($this->isValidUniqueUserDataByKey($userData, 'username')) {
            return;
        }
        $this->errors->addMessage('emailAddress', new DoesAlreadyExistMessage());
    }

    private function validateUniqueEmailAddress(array $userData): void
    {
        if ($this->isValidUniqueUserDataByKey($userData, 'emailAddress')) {
            return;
        }
        $this->errors->addMessage('emailAddress', new DoesAlreadyExistMessage());
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
        if ($key === 'emailAddress') {
            $user = $this->userRepository->findByEmailAddress(
                EmailAddress::fromString((string)$userData[$key])
            );
        } else if ($key === 'username') {
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