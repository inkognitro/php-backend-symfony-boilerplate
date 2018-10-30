<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Validation\Rule;

use App\Packages\Common\Application\Validation\Messages\Message;
use App\Packages\Common\Application\Validation\Messages\MustBeAnEmailAddressMessage;
use App\Packages\Common\Application\Validation\Messages\MustBeAStringMessage;
use App\Packages\Common\Application\Validation\Rule;

final class EmptyOrEmailAddressRule implements Rule
{
    public function getValidationError($data): ?Message
    {
        if(!is_string($data)) {
            return new MustBeAStringMessage();
        }

        if(strlen($data) === 0) {
            return null;
        }

        if(filter_var($data, FILTER_VALIDATE_EMAIL)) {
            return new MustBeAnEmailAddressMessage();
        }

        return null;
    }
}