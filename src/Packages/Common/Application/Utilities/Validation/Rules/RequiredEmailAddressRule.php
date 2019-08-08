<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Utilities\Validation\Rules;

use App\Packages\Common\Application\Utilities\Validation\Messages\Message;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustBeAnEmailAddressMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustBeAStringMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustNotBeEmptyMessage;

final class RequiredEmailAddressRule implements Rule
{
    /** @param $emailAddress mixed */
    public function findError($emailAddress): ?Message
    {
        if(!is_string($emailAddress)) {
            return new MustBeAStringMessage();
        }
        if(strlen($emailAddress) === 0) {
            return new MustNotBeEmptyMessage();
        }
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL, [FILTER_FLAG_EMAIL_UNICODE])) {
            return new MustBeAnEmailAddressMessage();
        }
        return null;
    }
}