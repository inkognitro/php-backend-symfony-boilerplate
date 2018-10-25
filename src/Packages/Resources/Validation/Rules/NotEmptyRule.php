<?php declare(strict_types=1);

namespace App\Packages\Resources\Validation\Rules;

final class NotEmptyRule implements Rule
{
    public function getValidationError($data): ?string
    {
        $data = (string)$data;
        if(strlen(trim($data)) === 0) {
            return 'must not be empty';
        }
        return null;
    }
}