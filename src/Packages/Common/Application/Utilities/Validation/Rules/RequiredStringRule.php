<?php declare(strict_types=1);

namespace App\Packages\Common\Utilities\Validation\Rules;

use App\Packages\Common\Utilities\Validation\Messages\Message;
use App\Packages\Common\Utilities\Validation\Messages\MustBeAStringMessage;
use App\Packages\Common\Utilities\Validation\Messages\MustNotBeEmptyMessage;

final class RequiredStringRule implements Rule
{
    /** @param $text mixed */
    public function findError($text): ?Message
    {
        if(!is_string($text)) {
            return new MustBeAStringMessage();
        }
        if(strlen(trim($text)) === 0) {
            return new MustNotBeEmptyMessage();
        }
        return null;
    }
}