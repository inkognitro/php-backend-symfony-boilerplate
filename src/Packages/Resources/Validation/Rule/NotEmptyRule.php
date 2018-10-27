<?php declare(strict_types=1);

namespace App\Packages\Resources\Validation\Rule;

use App\Packages\Resources\Validation\Messages\Message;
use App\Packages\Resources\Validation\Messages\MustNotBeEmptyMessage;
use App\Packages\Resources\Validation\Rule;

final class NotEmptyRule implements Rule
{
    public function getValidationError($data): ?Message
    {
        $data = (string)$data;
        if(strlen(trim($data)) === 0) {
            return new MustNotBeEmptyMessage();
        }
        return null;
    }
}