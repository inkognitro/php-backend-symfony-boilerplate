<?php declare(strict_types=1);

namespace App\Packages\Resources\Validation;

use App\Packages\Resources\Validation\Messages\Message;

interface Rule
{
    /** @param $data mixed */
    public function getValidationError($data): ?Message;
}