<?php declare(strict_types=1);

namespace App\Packages\Resources\Validation\Rules;

interface Rule
{
    /** @param $data mixed */
    public function getValidationError($data): ?string;
}