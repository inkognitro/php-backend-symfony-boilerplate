<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Validation\Rules;

use App\Packages\Common\Domain\Validation\Messages\Message;
use App\Packages\Common\Domain\Validation\Messages\MustBeAnEmailAddressMessage;
use App\Packages\Common\Domain\Validation\Messages\MustBeAStringMessage;

final class RequiredEmailAddressRule
{
    /** @param $emailAddress mixed */
    public static function findError($emailAddress): ?Message
    {
        if(!is_string($emailAddress)) {
            return new MustBeAStringMessage();
        }
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            return new MustBeAnEmailAddressMessage();
        }
        return null;
    }
}