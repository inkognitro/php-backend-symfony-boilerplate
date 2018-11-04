<?php declare(strict_types=1);

namespace App\Resources\Application\User;

use App\Packages\Common\Application\Validation\Rule\EmptyOrEmailAddressRule;
use App\Packages\Common\Application\Validation\Rule\EmptyOrUuidRule;
use App\Packages\Common\Application\Validation\Rule\NotEmptyRule;
use App\Packages\Common\Application\Validation\Validator;
use App\Resources\Application\ResourceDataValidator;

final class UserDataDataValidator extends ResourceDataValidator
{
    private $validator;

    public function __construct(Validator $validator)
    {
        parent::__construct();
        $this->validator = $validator;
    }

    protected function validateData(array $userData): void
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
            if(!isset($userData[$attributeName])) {
                continue;
            }
            $message = $this->validator->getMessageFromValidation($userData, $rules);
            if ($message !== null) {
                $this->errors->addMessage($attributeName, $message);
            }
        }
    }
}