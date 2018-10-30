<?php declare(strict_types=1);

namespace App\Resources\Application\User;

use App\Packages\Common\Application\Validation\Rule\EmptyOrEmailAddressRule;
use App\Packages\Common\Application\Validation\Rule\EmptyOrUuidRule;
use App\Packages\Common\Application\Validation\Rule\NotEmptyRule;
use App\Packages\Common\Application\Validation\Validator;
use App\Resources\Application\AbstractValidator;

final class UserValidator extends AbstractValidator
{
    private $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    protected function validateData(array $data): void
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
            $message = $this->validator->getMessageFromValidation($data, $rules);
            if ($message !== null) {
                $this->errors[$attributeName] = $message;
            }
        }
    }
}