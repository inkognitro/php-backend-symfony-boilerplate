<?php declare(strict_types=1);

namespace App\Packages\Resources\Validation;

final class NotEmptyRule implements Rule
{
    public function getErrorMessage(): string
    {
        return 'must not be empty';
    }

    public function isValid($data): bool
    {
        $data = (string)$data;
        if(strlen(trim($data)) === 0) {
            return false;
        }
        return true;
    }
}