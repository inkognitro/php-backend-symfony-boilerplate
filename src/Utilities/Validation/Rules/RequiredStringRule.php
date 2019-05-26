<?php declare(strict_types=1);

namespace App\Utilities\Validation\Rules;

use App\Utilities\Validation\Messages\Message;
use App\Utilities\Validation\Messages\MustBeAStringMessage;
use App\Utilities\Validation\Messages\MustNotBeEmptyMessage;

final class RequiredStringRule
{
    /** @param $text mixed */
    public static function findError($text): ?Message
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