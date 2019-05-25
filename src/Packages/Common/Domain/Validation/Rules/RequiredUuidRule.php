<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Validation\Rules;

use App\Packages\Common\Domain\Validation\Messages\Message;
use App\Packages\Common\Domain\Validation\Messages\MustBeAStringMessage;
use App\Packages\Common\Domain\Validation\Messages\MustBeAUuidMessage;
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