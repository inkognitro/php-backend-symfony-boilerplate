<?php declare(strict_types=1);

namespace App\Resources\Application\User;

use App\Packages\Common\Application\Validation\Rule\EmptyOrEmailAddressRule;
use App\Packages\Common\Application\Validation\Rule\EmptyOrUuidRule;
use App\Packages\Common\Application\Validation\Rule\NotEmptyRule;
use App\Packages\Common\Application\Validation\Validator;
use App\Resources\Application\ResourceDataValidator;

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
            if (!isset($userData[$attributeName])) {
                continue;
            }
            $message = $this->validator->getMessageFromValidation($userData, $rules);
            if ($message !== null) {
                $this->errors->addMessage($attributeName, $message);
            }
        }
    }

    private function validateUniqueEmailAddress(array $userData): void
    {
        if(!isset($userData['emailAddress'])) {
            return;
        }
        if($this->errors->doesMessageKeyExist('emailAddress')) {
            return;
        }
        $user = $this->userRepository->findByEmailAddress($userData['emailAddress']);

    }
}