<?php declare(strict_types=1);

namespace App\Utilities\Validation\Rules;

use App\Utilities\Validation\Messages\Message;
use App\Utilities\Validation\Messages\MustBeAnEmailAddressMessage;
use App\Utilities\Validation\Messages\MustBeAStringMessage;

final class RequiredEmailAddressRule implements Rule
{
    /** @param $emailAddress mixed */
    public function findError($emailAddress): ?Message
    {
        if(!is_string($emailAddress)) {
            return new MustBeAStringMessage();
        }
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL, [FILTER_FLAG_EMAIL_UNICODE])) {
            return new MustBeAnEmailAddressMessage();
        }
        return null;
    }
}