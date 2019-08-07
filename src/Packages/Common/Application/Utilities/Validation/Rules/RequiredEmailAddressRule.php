<?php declare(strict_types=1);

namespace App\Packages\Common\Utilities\Validation\Rules;

use App\Packages\Common\Utilities\Validation\Messages\Message;
use App\Packages\Common\Utilities\Validation\Messages\MustBeAnEmailAddressMessage;
use App\Packages\Common\Utilities\Validation\Messages\MustBeAStringMessage;

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