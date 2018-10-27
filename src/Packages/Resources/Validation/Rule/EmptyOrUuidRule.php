<?php declare(strict_types=1);

namespace App\Packages\Resources\Validation\Rule;

use App\Packages\Resources\Validation\Messages\Message;
use App\Packages\Resources\Validation\Messages\MustBeAStringMessage;
use App\Packages\Resources\Validation\Messages\MustBeAUuidMessage;
use App\Packages\Resources\Validation\Rule;
use Ramsey\Uuid\Uuid;

final class EmptyOrUuidRule implements Rule
{
    public function getValidationError($data): ?Message
    {
        if(!is_string($data)) {
            return new MustBeAStringMessage();
        }
        if(strlen($data) === 0) {
            return null;
        }
        if(!Uuid::isValid((string)$data)) {
            return new MustBeAUuidMessage();
        }
        return null;
    }
}