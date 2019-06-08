<?php declare(strict_types=1);

namespace App\Utilities\Validation\Rules;

use App\Utilities\Validation\Messages\Message;
use App\Utilities\Validation\Messages\MustBeAStringMessage;
use App\Utilities\Validation\Messages\MustBeAUuidMessage;
use Ramsey\Uuid\Uuid;

final class RequiredUuidRule
{
    /** @param $uuid mixed */
    public static function findError($uuid): ?Message
    {
        if(!is_string($uuid)) {
            return new MustBeAStringMessage();
        }
        if(strlen(trim($uuid)) === 0) {
            return null;
        }
        if(!Uuid::isValid($uuid)) {
            return new MustBeAUuidMessage();
        }
        return null;
    }
}