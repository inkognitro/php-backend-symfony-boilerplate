<?php declare(strict_types=1);

namespace App\Resources\Validation;

interface ValidationRule
{
    public function getErrorMessage(): string;

    /** @param $data mixed */
    public function isValid($data): bool;
}