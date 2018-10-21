<?php declare(strict_types=1);

namespace App\Resources\Validation;

final class ValidationRules
{
    private $rules;

    /** @param $rules ValidationRule[] */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /** @return ValidationRule[] */
    public function toCollection(): array
    {
        return $this->rules;
    }
}