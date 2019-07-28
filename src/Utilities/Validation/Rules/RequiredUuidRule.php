<?php declare(strict_types=1);

namespace App\Utilities\Validation\Rules;

use App\Utilities\Validation\Messages\Message;
use App\Utilities\Validation\Messages\MustBeAStringMessage;
use App\Utilities\Validation\Messages\MustBeAUuidMessage;
use Ramsey\Uuid\Uuid;

final class RequiredUuidRule implements Rule
{
    /** @param $uuid mixed */
    public static function findError($uuid): ?Message
    {
        if(!is_string($uuid)) {
            return new MustBeAStringMessage();
        }
        if(!Uuid::isValid($uuid)) {
            return new MustBeAUuidMessage();
        }
        return null;
    }
}